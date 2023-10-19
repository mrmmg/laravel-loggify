@extends('loggify::layout.theme', ['information' => $information])

@section('main')
    <div class="container my-3">
        <div class="accordion" id="accordion">
            @foreach($logs as $key => $log)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{$key}}"
                                aria-expanded="false" aria-controls="{{$key}}">

                            <div class="d-flex justify-content-between flex-grow-1">
                                <span>{{$log['message']}}</span>
                                <span
                                    class="me-2">{{\Carbon\Carbon::parse($log['datetime'])->format('Y-m-d H:i:s.u')}}</span>
                            </div>
                        </button>
                    </h2>
                    <div id="{{$key}}" class="accordion-collapse collapse" aria-labelledby="{{$key}}"
                         data-bs-parent="#accordion">
                        <div class="accordion-body">
                            @if(!empty($log['extra']))
                                <div class="my-3">
                                    <h6>Extra:</h6>
                                    <pre>{{var_export($log['extra'])}}</pre>
                                </div>
                            @endif

                            @if(!empty($log['context']))
                                <div class="my-4">
                                    <h6>Context:</h6>
                                    <pre>{{var_export($log['context'])}}</pre>
                                </div>
                            @endif

                            @if(!is_null($log['trace']))
                                <div class="accordion-item mt-2" id="{{$key}}_trace_item" style="border-radius: .2rem">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#{{$key}}_trace"
                                                aria-expanded="false" aria-controls="{{$key}}_trace">
                                            Trace
                                        </button>
                                    </h2>

                                    <div id="{{$key}}_trace" class="accordion-collapse collapse"
                                         aria-labelledby="{{$key}}_trace"
                                         data-bs-parent="#{{$key}}_trace_item">
                                        <div class="accordion-body my-3">
                                            <pre>{{ var_export( $log['trace'] ) }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <hr>
                            <div class="d-flex justify-content-between mt-4">
                                <div>
                                    Tags:

                                    @php

                                        $type = strtolower($log['level_name']);

                                        $badge_class = match($log['level']){
                                            \Monolog\Logger::INFO => 'text-bg-info',
                                            \Monolog\Logger::NOTICE => 'text-bg-info',
                                            \Monolog\Logger::WARNING => 'text-bg-warning',
                                            \Monolog\Logger::ALERT => 'text-bg-warning',
                                            \Monolog\Logger::ERROR => 'text-bg-danger',
                                            \Monolog\Logger::EMERGENCY => 'text-bg-danger',
                                            \Monolog\Logger::CRITICAL => 'text-bg-danger',
                                            \Monolog\Logger::DEBUG => 'text-bg-secondary'
                                        };
                                    @endphp

                                    @foreach($log['tags'] as $tag)
                                        <a href="{{route('loggify.view_tag', ['tag' => $tag])}}"><span class="badge {{$badge_class}}">{{$tag}}</span></a>
                                    @endforeach
                                </div>

                                <div class="flex-row-reverse">
                                    <span class="text-sm text-secondary">{{$log['uuid']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
