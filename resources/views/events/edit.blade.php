@extends('layouts.main')

@section('title', 'Editando' . $event->titulo)

@section('content')

<div id="event-create-container" class="col-md-6 offset-md-3">
    <h1>Editando o evento: {{$event->titulo}}</h1>
    <form action="/events/update/{{$event->id}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="image">Imagem:</label>
            <input type="file" id="image" name="image" class="form-control-file">
            <img src="/img/events/{{$event->image}}" alt="{{$event->titulo}}" class="img-preview">
        </div>
        <div class="form-group">
            <label for="titulo">Evento:</label>
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Nome do Evento" value="{{$event->titulo}}">
        </div>
        <div class="form-group">
            <label for="date">Data do Evento:</label>
            <input type="date" class="form-control" id="date" name="date" value="{{$event->date->format('Y-m-d')}}">
        </div>
        <div class="form-group">
            <label for="titulo">Cidade:</label>
            <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Local do Evento" value="{{$event->cidade}}">
        </div>
        <div class="form-group">
            <label for="titulo">O evento é privado?</label>
            <select name="private" id="private" class="form-control">
                <option value ="0">Não</option>
                <option value="1" {{ $event->private == 1 ? "selected='selected'" : ""}}>Sim</option>
            </select>
        </div>
        <div class="form-group">
           <label for="titulo">Descrição:</label>
            <textarea name="descricao" id="descricao" class="form-control" placeholder="O que vai acontecer no Evento?">{{$event->descricao}}</textarea>
        </div> 
        <div class="form-group">
           <label for="titulo">Adicionar itens de infraestrutura:</label>
           <div class="form-group">
                <input type="checkbox" name="items[]" value="Cadeiras"> Cadeiras
           </div>
           <div class="form-group">
                <input type="checkbox" name="items[]" value="Palco"> Palco
           </div>
           <div class="form-group">
                <input type="checkbox" name="items[]" value="Jantar Grátis"> Jantar Grátis
           </div>
           <div class="form-group">
                <input type="checkbox" name="items[]" value="Brindes"> Brindes
           </div>
        </div> 
        
        <input type="submit" class="btn btn-primary" value="Salvar Edições">
    </form>
</div>
@endsection