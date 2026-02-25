import EmptyState from '@/Components/EmptyState';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Index({ orders }) {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-lg font-semibold text-gray-900">Orders</h2>
            }
        >
            <Head title="Orders" />

            <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                {orders.data.length === 0 ? (
                    <EmptyState
                        title="No orders imported yet"
                        description="Send a JSON payload to POST /api/orders/import to get started."
                    />
                ) : (
                    <>
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b border-gray-100 text-left">
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Date
                                    </th>
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Store
                                    </th>
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Items
                                    </th>
                                    <th className="px-4 py-3 text-xs font-medium uppercase tracking-wide text-gray-400">
                                        Receipt ref
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-gray-100">
                                {orders.data.map((order) => (
                                    <tr
                                        key={order.id}
                                        className="hover:bg-gray-50"
                                    >
                                        <td className="px-4 py-3 font-medium text-gray-900">
                                            {order.ordered_at}
                                        </td>
                                        <td className="px-4 py-3 text-gray-600">
                                            {order.store}
                                        </td>
                                        <td className="px-4 py-3 text-gray-600">
                                            {order.items_count}
                                        </td>
                                        <td className="px-4 py-3 text-gray-400">
                                            {order.receipt_reference ?? '—'}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>

                        {/* Pagination */}
                        {(orders.prev_page_url || orders.next_page_url) && (
                            <div className="flex items-center justify-between border-t border-gray-100 px-4 py-3">
                                <p className="text-xs text-gray-400">
                                    {orders.from}–{orders.to} of {orders.total}
                                </p>
                                <div className="flex gap-2">
                                    {orders.prev_page_url && (
                                        <Link
                                            href={orders.prev_page_url}
                                            className="rounded border border-gray-200 px-3 py-1 text-xs text-gray-600 hover:border-gray-300 hover:text-gray-900"
                                        >
                                            Previous
                                        </Link>
                                    )}
                                    {orders.next_page_url && (
                                        <Link
                                            href={orders.next_page_url}
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
