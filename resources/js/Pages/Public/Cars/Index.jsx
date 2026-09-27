import CarCard from '@/Components/CarCard';
import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';

export default function Index({ cars }) {
    const items = cars?.data ?? [];
    const links = cars?.links ?? [];

    return (
        <PublicLayout>
            <Head title="A nossa frota" />

            <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <h1 className="mb-2 text-3xl font-bold text-gray-900">
                    A nossa frota
                </h1>
                <p className="mb-8 text-gray-600">
                    Consulte fotos, capacidades, estado e preço de cada viatura.
                </p>

                <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    {items.length > 0 ? (
                        items.map((car) => <CarCard key={car.id} car={car} />)
                    ) : (
                        <div className="col-span-3 py-12 text-center text-gray-600">
                            <p className="text-xl">
                                Nenhum carro disponível no momento.
                            </p>
                        </div>
                    )}
                </div>

                {links.length > 3 && (
                    <div className="mt-8 flex flex-wrap justify-center gap-2">
                        {links.map((link, index) =>
                            link.url ? (
                                <Link
                                    key={index}
                                    href={link.url}
                                    preserveScroll
                                    className={`rounded px-3 py-2 text-sm ${
                                        link.active
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-white text-gray-700 shadow hover:bg-gray-50'
                                    }`}
                                    dangerouslySetInnerHTML={{
                                        __html: link.label,
                                    }}
                                />
                            ) : (
                                <span
                                    key={index}
                                    className="rounded px-3 py-2 text-sm text-gray-400"
                                    dangerouslySetInnerHTML={{
                                        __html: link.label,
                                    }}
                                />
                            ),
                        )}
                    </div>
                )}
            </div>
        </PublicLayout>
    );
}
