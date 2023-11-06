@foreach($article->config['custom_bloc'] as $bloc_config)
    @if(isset($bloc_config['fields']) && isset($bloc->name) && $bloc_config['name'] == $bloc->name )
        <div class="bloc box sortable-item" id="bloc_{{ $key }}" data-sortable="{{ $key }}">
            <div class="box-header">
                <h2 class="box-title">Bloc {{ $bloc_config['label'] }}</h2>
                <div class="btn-toolbar">
                    <div class="btn sortable-move" data-toggle="tooltip" title="Trier"><span class="fa fa-arrows-alt"></span></div>
                    <button class="btn btn-outline-dark remove-bloc" type="button" id="{{ $key }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <input type="hidden" name="custom_blocs[{{ $key }}][name]" value="{{ $bloc->name }}">
                <div id="fields-bloc-{{ $key }}" class="fields-bloc">
                    @foreach($bloc_config['fields'] as $k => $field)
                        @php
                            $field_name = 'custom_blocs[' . $key . '][fields][' . $field['name'] . ']';
                            $field_value = old('custom_blocs.'.$key.'.fields.'.$field['name'], $bloc->fields->{$field['name']} ?? ($field['type'] == "repeater" ? [] : '') );
                        @endphp
                        <x-admin::custom :field="$field" :name="$field_name" :value="$field_value" />
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach
