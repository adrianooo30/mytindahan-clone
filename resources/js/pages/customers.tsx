import { Head } from '@inertiajs/react';
import { Search, WalletCards } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { customers } from '@/routes';

type CustomerRow = {
    id: number;
    name: string;
    phone: string;
    balance: number;
    status: string;
};

type CustomersProps = {
    customers?: CustomerRow[];
};

const currencyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

export default function CustomersPage({ customers: customerRows = [] }: CustomersProps) {
    return (
        <>
            <Head title="Customers" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
                <div className="flex flex-col gap-4 rounded-xl border border-border/70 bg-card/80 p-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Customers</h1>
                    </div>
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <div className="relative min-w-[220px]">
                            <Search className="absolute top-2.5 left-2.5 h-4 w-4 text-muted-foreground" />
                            <Input className="pl-8" placeholder="Search customers..." />
                        </div>
                        <Button>Add Customer</Button>
                    </div>
                </div>

                <Card className="border-border/70 bg-card/80">
                    <CardHeader className="flex flex-row items-center justify-between">
                        <CardTitle className="flex items-center gap-2">
                            <WalletCards className="h-4 w-4" />
                            Credit ledger
                        </CardTitle>
                    </CardHeader>
                    <CardContent className="p-0">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th className="px-4 py-3 font-medium">Customer</th>
                                        <th className="px-4 py-3 font-medium">Phone</th>
                                        <th className="px-4 py-3 font-medium">Balance</th>
                                        <th className="px-4 py-3 font-medium">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {customerRows.map((customer) => (
                                        <tr key={customer.id} className="border-t border-border/70">
                                            <td className="px-4 py-3 font-medium">{customer.name}</td>
                                            <td className="px-4 py-3 text-muted-foreground">{customer.phone}</td>
                                            <td className="px-4 py-3">{currencyFormatter.format(customer.balance)}</td>
                                            <td className="px-4 py-3">
                                                <Badge
                                                    variant={
                                                        customer.balance > 0
                                                            ? 'secondary'
                                                            : 'default'
                                                    }
                                                >
                                                    {customer.balance > 0 ? 'Active' : 'Settled'}
                                                </Badge>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

CustomersPage.layout = {
    breadcrumbs: [
        {
            title: 'Customers',
            href: customers(),
        },
    ],
};
