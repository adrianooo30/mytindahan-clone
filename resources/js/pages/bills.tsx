import { Head } from '@inertiajs/react';
import { ReceiptText } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { bills } from '@/routes';

type BillPayment = {
    id: number;
    customerId?: number | null;
    biller: string;
    accountNumber: string;
    accountName?: string | null;
    amount: number;
    serviceFee: number;
    total: number;
    referenceNumber?: string | null;
    status: string;
    paidAt?: string | null;
};

type BillsProps = {
    payments?: BillPayment[];
};

const currencyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

export default function BillsPage({ payments = [] }: BillsProps) {
    return (
        <>
            <Head title="Bills" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
                <div className="rounded-xl border border-border/70 bg-card/80 p-4">
                    <h1 className="text-2xl font-semibold tracking-tight">Bills</h1>
                </div>

                <Card className="border-border/70 bg-card/80">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <ReceiptText className="h-4 w-4 text-primary" />
                            Payments
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-3">
                            {payments.length > 0 ? (
                                payments.map((payment) => (
                                    <div key={payment.id} className="flex flex-col gap-2 rounded-lg border border-border/80 p-3 md:flex-row md:items-center md:justify-between">
                                        <div>
                                            <div className="font-medium">{payment.biller}</div>
                                            <div className="text-sm text-muted-foreground">
                                                {payment.accountNumber} • {payment.accountName ?? 'No account name'}
                                            </div>
                                        </div>
                                        <div className="flex items-center gap-3">
                                            <span className="font-medium">{currencyFormatter.format(payment.total)}</span>
                                            <span className="rounded bg-muted px-2 py-1 text-xs">{payment.status}</span>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="text-sm text-muted-foreground">No bill payments yet.</div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

BillsPage.layout = {
    breadcrumbs: [
        {
            title: 'Bills',
            href: bills(),
        },
    ],
};
