import { Link, usePage } from '@inertiajs/react';

export default function PublicLayout({ children }) {
    const { auth, appName } = usePage().props;
    const name = appName || 'RentACar';

    const navClass = (active) =>
        `${active ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium`;

    return (
        <div className="min-h-screen bg-gray-50 font-sans antialiased">
            <nav className="bg-white shadow-lg">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="flex h-16 justify-between">
                        <div className="flex">
                            <div className="flex shrink-0 items-center">
                                <Link
                                    href={route('public.home')}
                                    className="text-2xl font-bold text-blue-600"
                                >
                                    {name}
                                </Link>
                            </div>
                            <div className="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <Link
                                    href={route('public.home')}
                                    className={navClass(route().current('public.home'))}
                                >
                                    Início
                                </Link>
                                <Link
                                    href={route('public.cars.index')}
                                    className={navClass(
                                        route().current('public.cars.*'),
                                    )}
                                >
                                    Carros Disponíveis
                                </Link>
                                <Link
                                    href={route('public.reservations.create')}
                                    className={navClass(
                                        route().current('public.reservations.*'),
                                    )}
                                >
                                    Fazer Reserva
                                </Link>
                            </div>
                        </div>
                        <div className="hidden sm:ml-6 sm:flex sm:items-center">
                            {auth?.user ? (
                                <>
                                    <Link
                                        href={route('client.dashboard')}
                                        className="rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700"
                                    >
                                        Área do Cliente
                                    </Link>
                                    <Link
                                        href={route('logout')}
                                        method="post"
                                        as="button"
                                        className="rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700"
                                    >
                                        Sair
                                    </Link>
                                </>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700"
                                    >
                                        Entrar
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="ml-4 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                                    >
                                        Registar
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            <main>{children}</main>

            <footer className="mt-16 bg-gray-800 text-white">
                <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <div>
                            <h3 className="mb-4 text-lg font-semibold">Sobre Nós</h3>
                            <p className="text-gray-400">
                                A melhor solução para aluguer de carros em Angola.
                                Qualidade, confiança e preços competitivos.
                            </p>
                        </div>
                        <div>
                            <h3 className="mb-4 text-lg font-semibold">Contactos</h3>
                            <p className="text-gray-400">Email: info@rentacar.com</p>
                            <p className="text-gray-400">Telefone: +244 923 456 789</p>
                        </div>
                        <div>
                            <h3 className="mb-4 text-lg font-semibold">Links Úteis</h3>
                            <ul className="space-y-2 text-gray-400">
                                <li>Termos e Condições</li>
                                <li>Política de Privacidade</li>
                                <li>FAQ</li>
                            </ul>
                        </div>
                    </div>
                    <div className="mt-8 border-t border-gray-700 pt-8 text-center text-gray-400">
                        <p>
                            &copy; {new Date().getFullYear()} {name}. Todos os
                            direitos reservados.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
