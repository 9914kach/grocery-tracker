import EmptyState from '@/Components/EmptyState';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Index({ unmatched }) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-lg font-semibold text-gray-900">
                            Needs Review
                        </h2>
                        <p className="mt-0.5 text-sm text-gray-500">
                            Store products that haven't been linked to a
                            canonical product yet.
                        </p>
                    </div>
                    {unmatched.total > 0 && (
                        <span className="rounded-full bg-amber-100 px-2.5 py-1 text-sm font-medium text-amber-700">
                            {unmatched.total} unmatched
                        </span>
                    )}
                </div>
            }
        >
            <Head title="Review" />

            <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                {unmatched.data.length === 0 ? (
                    <EmptyState
                        title="All products matched"
                        description="Every store product is linked to a canonical product."
                    />
                ) : (
                    <>
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b border-gray-100 text-left">
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Product name
                                    </th>
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Store
                                    </th>
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Unit size
                                    </th>
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        External ID
                                    </th>
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {unmatched.data.map((item) => (
                                    <tr
                                        key={item.id}
                                        className="hover:bg-gray-50"
                                    >
                                        <td className="px-4 py-3 font-medium text-gray-900">
                                            {item.name}
                                        </td>
                                        <td className="px-4 py-3 text-gray-600">
                                            {item.store}
                                        </td>
                                        <td className="px-4 py-3 text-gray-400">
                                            {item.unit_size ?? '—'}
                                        </td>
                                        <td className="px-4 py-3 font-mono text-xs text-gray-400">
                                            {item.external_id ?? item.barcode ?? '—'}
                                        </td>
                                        <td className="px-4 py-3">
                                            <button
                                                disabled
                                                className="rounded border border-gray-200 px-2.5 py-1 text-xs text-gray-400 cursor-not-allowed"
                                                title="Coming soon — matching pipeline"
                                            >
                                                Link product
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>

                        {/* Pagination */}
                        {(unmatched.prev_page_url || unmatched.next_page_url) && (
                            <div className="flex items-center justify-between border-t border-gray-100 px-4 py-3">
                                <p className="text-xs text-gray-400">
                                    {unmatched.from}–{unmatched.to} of{' '}
                                    {unmatched.total}
                                </p>
                                <div className="flex gap-2">
                                    {unmatched.prev_page_url && (
                                        <Link
                                            href={unmatched.prev_page_url}
                                            className="rounded border border-gray-200 px-3 py-1 text-xs text-gray-600 hover:border-gray-300 hover:text-gray-900"
                                        >
                                            Previous
                                        </Link>
                                    )}
                                    {unmatched.next_page_url && (
                                        <Link
                                            href={unmatched.next_page_url}
                                            className="rounded border border-gray-200 px-3 py-1 text-xs text-gray-600 hover:border-gray-300 hover:text-gray-900"
                                        >
                                            Next
                                        </Link>
                                    )}
                                </div>
                            </div>
                        )}
                    </>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
