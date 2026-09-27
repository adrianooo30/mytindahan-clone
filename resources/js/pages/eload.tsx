import { Head } from '@inertiajs/react';
import { Smartphone, Zap } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { eload } from '@/routes';

type EloadTransaction = {
    id: number;
    customerId?: number | null;
    mobileNumber: string;
    network: string;
    productName: string;
    amount: number;
    referenceNumber?: string | null;
    status: string;
    soldAt?: string | null;
};

type EloadProps = {
    transactions?: EloadTransaction[];
};

const currencyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

export default function EloadPage({ transactions = [] }: EloadProps) {
    return (
        <>
            <Head title="E-load" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
                <div className="rounded-xl border border-border/70 bg-card/80 p-4">
                    <h1 className="text-2xl font-semibold tracking-tight">E-load</h1>
                </div>

                <Card className="border-border/70 bg-card/80">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Smartphone className="h-4 w-4 text-primary" />
                            Transactions
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-3">
                            {transactions.length > 0 ? (
                                transactions.map((transaction) => (
                                    <div key={transaction.id} className="flex flex-col gap-2 rounded-lg border border-border/80 p-3 md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <div className="font-medium">{transaction.productName}</div>
                                            <div className="text-sm text-muted-foreground">
                                                {transaction.mobileNumber} • {transaction.network}
                                            </div>
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <span className="font-medium">{currencyFormatter.format(transaction.amount)}</span>
                                            <span className="rounded bg-muted px-2 py-1 text-xs">{transaction.status}</span>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="text-sm text-muted-foreground">No e-load transactions yet.</div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

EloadPage.layout = {
    breadcrumbs: [
        {
            title: 'E-load',
            href: eload(),
        },
    ],
};
