@extends('layouts.crm')

@section('content')
    <div class="cards-two">
        <section class="crm-panel simple-card">
            <h4 style="font-size: 30px; margin-bottom: 10px">{{ $settings['title'] }}</h4>
            <p>{{ $settings['body'] }}</p>
        </section>

        <section class="crm-panel simple-card">
            <h4>{{ $settings['cards'][0]['title'] }}</h4>
            <p>{{ $settings['cards'][0]['body'] }}</p>
        </section>
    </div>

    <div class="cards-half">
        <section class="crm-panel simple-card">
            <h4>{{ $settings['cards'][1]['title'] }}</h4>
            <p>{{ $settings['cards'][1]['body'] }}</p>
        </section>

        <section class="crm-panel simple-card">
            <h4>{{ $settings['cards'][2]['title'] }}</h4>
            <p>{{ $settings['cards'][2]['body'] }}</p>
        </section>
    </div>
@endsection
