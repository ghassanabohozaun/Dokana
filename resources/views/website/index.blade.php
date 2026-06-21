@extends('layouts.website.app')

@section('title', __('website.home'))

@section('content')
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-[#0b1121]">
        @if(optional(setting())->logo)
            <img src="{{ asset('uploads/settings/' . optional(setting())->logo) }}" alt="Logo" class="h-24 w-auto rounded shadow-sm mb-4">
        @else
            <i class="ph-fill ph-storefront text-6xl text-gray-300 dark:text-gray-700 mb-4"></i>
        @endif
        
        <h1 class="text-3xl font-bold text-gray-400 dark:text-gray-600">
            {{ optional(setting())->getTranslation('site_name', app()->getLocale()) ?: __('website.home') }}
        </h1>
        
        <a href="{{ route('website.casher.login') }}" class="mt-8 bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 transition-colors">
            {{ __('website.go_to_store') }}
        </a>
    </div>
@endsection
