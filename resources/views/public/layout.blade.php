<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RentACar') }} - Aluguer de Carros</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('public.home') }}" class="text-2xl font-bold text-blue-600">
                            {{ config('app.name', 'RentACar') }}
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('public.home') }}" class="border-blue-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Início
                        </a>
                        <a href="{{ route('public.cars.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Carros Disponíveis
                        </a>
                        <a href="{{ route('public.reservations.create') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Fazer Reserva
                        </a>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <a href="{{ route('client.dashboard') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Área do Cliente
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                Sair
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}" class="ml-4 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                            Registar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Sobre Nós</h3>
                    <p class="text-gray-400">A melhor solução para aluguer de carros em Angola. Qualidade, confiança e preços competitivos.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contactos</h3>
                    <p class="text-gray-400">Email: info@rentacar.com</p>
                    <p class="text-gray-400">Telefone: +244 923 456 789</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Links Úteis</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Termos e Condições</a></li>
                        <li><a href="#" class="hover:text-white">Política de Privacidade</a></li>
                        <li><a href="#" class="hover:text-white">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'RentACar') }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>

