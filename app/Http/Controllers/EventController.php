<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;

use App\Models\User;

class EventController extends Controller
{
    public function index()
    {

        $search = request('search');

        if ($search) {

            $events = Event::where(
                [
                    ['titulo', 'like', '%' . $search . '%']
                ]
            )->get();
        } else {
            $events = Event::all();
        }



        return view('welcome', ['events' => $events, 'search' => $search]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function entrar()
    {
        return view('entrar');
    }

    public function cadastrar()
    {
        return view('cadastrar');
    }

    public function store(Request $request)
    {

        $event = new Event;

        $event->titulo = $request->titulo;
        $event->date = $request->date;
        $event->cidade = $request->cidade;
        $event->private = $request->private;
        $event->descricao = $request->descricao;
        $event->items = $request->items;

        // Image Upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->image->move(public_path('img/events'), $imageName);
            $event->image = $imageName;
        }

        $user = auth()->user();

        $event->user_id = $user->id;

        $event->save();

        return redirect('/events/create')->with('Mensagem', 'Evento cadastrado com sucesso!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        $user = auth()->user();
        $hasUserJoined = false;

        if($user){
            $userEvents = $user->eventsAsParticipant->toArray();
            foreach($userEvents as $userEvent) {
                if($userEvent['id'] == $id){
                    $hasUserJoined = true;
                }
            }    
        }

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'hasUserJoined' => $hasUserJoined]);
    }

    public function dashboard()
    {
        $user = auth()->user();

        $events = $user->events;

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventsasparticipant'=>$eventsAsParticipant]);
    }

    public function destroy($id)
    {

        Event::findOrFail($id)->delete();

        return redirect('/dashboard')->with('Mensagem', 'Evento excluído com sucesso!');
    }

    public function edit($id)
    {
        $user = auth()->user();

        $event = Event::findOrFail($id);

        if($user->id != $event->user_id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request)
    {

        $data = $request->all();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $request->image->move(public_path('img/events'), $imageName);
            $data['image'] = $imageName;
        }

        Event::findOrFail($request->id)->update($data);
        return redirect('/dashboard')->with('Mensagem', 'Evento editado com sucesso!');
    }

    public function joinEvent($id) {

        $user = auth()->user();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('Mensagem', 'Sua presença está confirmada no evento ' . $event->titulo);
    }

    public function leaveEvent($id){
        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        return redirect('/dashboard')->with('Mensagem', 'Você deletou sua inscrição do evento: ' . $event->titulo);
    }
}
