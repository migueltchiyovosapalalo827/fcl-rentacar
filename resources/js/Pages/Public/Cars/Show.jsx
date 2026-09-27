import PublicLayout from '@/Layouts/PublicLayout';
import { Head, Link } from '@inertiajs/react';
import { useMemo, useState } from 'react';

function formatPrice(value) {
    return Number(value || 0).toLocaleString('pt-PT', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function formatKm(value) {
    return Number(value || 0).toLocaleString('pt-PT');
}

export default function Show({ car }) {
    const gallery = car.gallery?.length ? car.gallery : car.cover_url ? [car.cover_url] : [];
    const [activePhoto, setActivePhoto] = useState(gallery[0] ?? null);

    const specs = useMemo(
        () => [
            { label: 'Ano', value: car.year ?? '—' },
            { label: 'Cor', value: car.color ?? '—' },
            { label: 'Lugares', value: car.seats ? `${car.seats} pessoas` : '—' },
            { label: 'Portas', value: car.doors ?? '—' },
            { label: 'Malas', value: car.luggage_capacity ?? '—' },
            { label: 'Combustível', value: car.fuel_label ?? '—' },
            { label: 'Transmissão', value: car.transmission_label ?? '—' },
            { label: 'Ar condicionado', value: car.air_conditioning ? 'Sim' : 'Não' },
            { label: 'Quilometragem', value: `${formatKm(car.km)} km`, wide: true },
            { label: 'Estado operacional', value: car.status_label, wide: true },
        ],
        [car],
    );

    const badgeClass = car.occupied
        ? 'bg-amber-100 text-amber-800'
        : car.available
          ? 'bg-green-100 text-green-800'
          : 'bg-gray-100 text-gray-700';

    return (
        <PublicLayout>
            <Head title={`${car.brand} ${car.model}`} />

            <div className="mx-auto max-w-6xl px-4 py-12 sm:px-6 lg:px-8">
                <Link
                    href={route('public.cars.index')}
                    className="text-sm font-medium text-blue-600 hover:text-blue-800"
                >
                    ← Voltar aos carros
                </Link>

                <div className="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-5">
                    <div className="lg:col-span-3">
                        <div className="overflow-hidden rounded-lg bg-white shadow-lg">
                            <div className="flex h-80 items-center justify-center bg-gray-200">
                                {activePhoto ? (
                                    <img
                                        src={activePhoto}
                                        alt={`${car.brand} ${car.model}`}
                                        className="h-full w-full object-cover"
                                    />
                                ) : (
                                    <span className="text-gray-400">
                                        Sem fotografia
                                    </span>
                                )}
                            </div>

                            {gallery.length > 1 && (
                                <div className="grid grid-cols-4 gap-2 bg-gray-50 p-3 sm:grid-cols-6">
                                    {gallery.map((url) => (
                                        <button
                                            key={url}
                                            type="button"
                                            onClick={() => setActivePhoto(url)}
                                            className={`h-16 overflow-hidden rounded border-2 ${
                                                activePhoto === url
                                                    ? 'border-blue-600'
                                                    : 'border-transparent'
                                            }`}
                                        >
                                            <img
                                                src={url}
                                                alt=""
                                                className="h-full w-full object-cover"
                                            />
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>

                        <div className="mt-6 rounded-lg bg-white p-6 shadow-lg">
                            <h2 className="mb-3 text-xl font-semibold text-gray-900">
                                Descrição
                            </h2>
                            {car.description ? (
                                <p className="whitespace-pre-line leading-relaxed text-gray-700">
                                    {car.description}
                                </p>
                            ) : (
                                <p className="text-gray-500">
                                    Ainda não existe uma descrição para esta
                                    viatura.
                                </p>
                            )}
                        </div>
                    </div>

                    <div className="space-y-6 lg:col-span-2">
                        <div className="rounded-lg bg-white p-6 shadow-lg">
                            <div className="mb-4 flex items-start justify-between gap-3">
                                <div>
                                    <p className="text-sm text-gray-500">
                                        {car.category_label ?? 'Viatura'}
                                    </p>
                                    <h1 className="text-3xl font-bold text-gray-900">
                                        {car.brand} {car.model}
                                    </h1>
                                    <p className="mt-1 text-gray-600">
                                        Matrícula {car.plate_number}
                                    </p>
                                </div>
                                <span
                                    className={`inline-flex shrink-0 items-center rounded-full px-3 py-1 text-sm font-semibold ${badgeClass}`}
                                >
                                    {car.occupancy_label}
                                </span>
                            </div>

                            <div className="border-t border-gray-100 pt-4">
                                <p className="text-sm text-gray-500">Preço</p>
                                <p className="text-3xl font-bold text-blue-600">
                                    {formatPrice(car.price_per_day)}{' '}
                                    <span className="text-base font-medium text-gray-500">
                                        AOA/dia
                                    </span>
                                </p>
                            </div>

                            {car.occupied && car.current_rental_end && (
                                <p className="mt-4 rounded-lg bg-amber-50 p-3 text-sm text-amber-700">
                                    Esta viatura está ocupada até{' '}
                                    {car.current_rental_end}.
                                </p>
                            )}

                            {car.status === 'manutencao' && (
                                <p className="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">
                                    Esta viatura encontra-se em manutenção.
                                </p>
                            )}

                            <div className="mt-6">
                                {car.available ? (
                                    <Link
                                        href={route(
                                            'public.reservations.create',
                                            { car_id: car.id },
                                        )}
                                        className="block w-full rounded-lg bg-blue-600 px-6 py-3 text-center font-semibold text-white hover:bg-blue-700"
                                    >
                                        Reservar esta viatura
                                    </Link>
                                ) : (
                                    <button
                                        type="button"
                                        disabled
                                        className="block w-full cursor-not-allowed rounded-lg bg-gray-200 px-6 py-3 text-center font-semibold text-gray-500"
                                    >
                                        Indisponível para reserva
                                    </button>
                                )}
                            </div>
                        </div>

                        <div className="rounded-lg bg-white p-6 shadow-lg">
                            <h2 className="mb-4 text-lg font-semibold text-gray-900">
                                Capacidades e características
                            </h2>
                            <dl className="grid grid-cols-2 gap-4 text-sm">
                                {specs.map((spec) => (
                                    <div
                                        key={spec.label}
                                        className={spec.wide ? 'col-span-2' : ''}
                                    >
                                        <dt className="text-gray-500">
                                            {spec.label}
                                        </dt>
                                        <dd className="font-semibold text-gray-900">
                                            {spec.value}
                                        </dd>
                                    </div>
                                ))}
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}
