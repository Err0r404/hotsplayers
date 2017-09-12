{!! Form::open(array('route' => 'route.name', 'method' => 'POST')) !!}
	<ul>
		<li>
			{!! Form::label('type', 'Type:') !!}
			{!! Form::text('type') !!}
		</li>
		<li>
			{!! Form::label('date', 'Date:') !!}
			{!! Form::text('date') !!}
		</li>
		<li>
			{!! Form::label('length', 'Length:') !!}
			{!! Form::text('length') !!}
		</li>
		<li>
			{!! Form::label('api_id', 'Api_id:') !!}
			{!! Form::text('api_id') !!}
		</li>
		<li>
			{!! Form::label('map_id', 'Map_id:') !!}
			{!! Form::text('map_id') !!}
		</li>
		<li>
			{!! Form::submit() !!}
		</li>
	</ul>
{!! Form::close() !!}