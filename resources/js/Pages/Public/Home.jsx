import CarCard from '@/Components/CarCard';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

export default function Home({ featuredCars = [] }) {
    return (
        <PublicLayout>
            <Head title="Início" />

            <div className="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
                <div className="mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="mb-4 text-4xl font-bold md:text-5xl">
                            Alugue o Carro Perfeito para Você
                        </h1>
                        <p className="mb-8 text-xl text-blue-100">
                            Encontre o veículo ideal para suas necessidades com
                            os melhores preços
                        </p>
                        <Link
                            href={route('public.reservations.create')}
                            className="inline-block rounded-lg bg-white px-8 py-3 font-semibold text-blue-600 transition hover:bg-gray-100"
                        >
                            Fazer Reserva Agora
                        </Link>
                    </div>
                </div>
            </div>

            <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div className="mb-12 text-center">
                    <h2 className="mb-4 text-3xl font-bold text-gray-900">
                        Por Que Escolher-Nos?
                    </h2>
                    <p className="text-gray-600">
                        Oferecemos a melhor experiência em aluguer de carros
                    </p>
                </div>

                <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div className="rounded-lg bg-white p-6 text-center shadow-md">
                        <div className="mb-4 text-4xl">🚗</div>
                        <h3 className="mb-2 text-xl font-semibold">Frota Variada</h3>
                        <p className="text-gray-600">
                            Carros modernos e bem mantidos para todas as
                            necessidades
                        </p>
                    </div>
                    <div className="rounded-lg bg-white p-6 text-center shadow-md">
                        <div className="mb-4 text-4xl">💰</div>
                        <h3 className="mb-2 text-xl font-semibold">
                            Preços Competitivos
                        </h3>
                        <p className="text-gray-600">
                            Os melhores preços do mercado sem comprometer a
                            qualidade
                        </p>
                    </div>
                    <div className="rounded-lg bg-white p-6 text-center shadow-md">
                        <div className="mb-4 text-4xl">✅</div>
                        <h3 className="mb-2 text-xl font-semibold">Reserva Fácil</h3>
                        <p className="text-gray-600">
                            Processo simples e rápido para fazer sua reserva
                            online
                        </p>
                    </div>
                </div>
            </div>

            <div className="bg-gray-100 py-16">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div className="mb-12 text-center">
                        <h2 className="mb-4 text-3xl font-bold text-gray-900">
                            Carros em Destaque
                        </h2>
                        <Link
                            href={route('public.cars.index')}
                            className="text-blue-600 hover:text-blue-800"
                        >
                            Ver Todos os Carros →
                        </Link>
                    </div>

                    <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
                        {featuredCars.length > 0 ? (
                            featuredCars.map((car) => (
                                <CarCard key={car.id} car={car} compact />
                            ))
                        ) : (
                            <div className="col-span-3 py-8 text-center text-gray-600">
                                <p>Nenhum carro disponível no momento.</p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
