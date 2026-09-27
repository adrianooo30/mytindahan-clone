import { Head } from '@inertiajs/react';
import { BarChart3, Download, TrendingUp } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { reports } from '@/routes';

type ReportsProps = {
    salesTotal?: number;
    profitTotal?: number;
    utangBalance?: number;
    expenseTotal?: number;
};

const currencyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

export default function ReportsPage({
    salesTotal = 0,
    profitTotal = 0,
    utangBalance = 0,
    expenseTotal = 0,
}: ReportsProps) {
    const overview = [
        { label: 'Sales', value: currencyFormatter.format(salesTotal), trend: '+8.5%' },
        { label: 'Profit', value: currencyFormatter.format(profitTotal), trend: '+5.2%' },
        { label: 'Utang', value: currencyFormatter.format(utangBalance), trend: '-2.1%' },
        { label: 'Expenses', value: currencyFormatter.format(expenseTotal), trend: '+1.1%' },
    ];

    return (
        <>
            <Head title="Reports" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
                <div className="flex flex-col gap-3 rounded-xl border border-border/70 bg-card/80 p-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Reports</h1>
                    </div>
                    <Button className="gap-2">
                        <Download className="h-4 w-4" />
                        Export
                    </Button>
                </div>

                <div className="grid gap-4 md:grid-cols-3">
                    {overview.map((item) => (
                        <Card key={item.label} className="border-border/70 bg-card/80">
                            <CardHeader>
                                <CardTitle className="text-sm font-medium text-muted-foreground">
                                    {item.label}
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-semibold">{item.value}</div>
                                <div className="mt-2 flex items-center gap-2 text-xs text-emerald-500">
                                    <TrendingUp className="h-3.5 w-3.5" />
                                    {item.trend}
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <Card className="border-border/70 bg-card/80">
                    <CardHeader className="flex flex-row items-center justify-between">
                        <CardTitle className="flex items-center gap-2">
                            <BarChart3 className="h-4 w-4" />
                            Performance trend
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="flex h-56 items-end gap-3 rounded-xl border border-border/80 bg-muted/20 p-4">
                            {[45, 58, 52, 70, 60, 85, 92, 78, 88, 96, 110, 120].map((value, index) => (
                                <div key={index} className="flex flex-1 flex-col items-center justify-end gap-2">
                                    <div
                                        className="w-full rounded-t-md bg-gradient-to-t from-primary to-primary/60"
                                        style={{ height: `${value}%` }}
                                    />
                                    <span className="text-[10px] text-muted-foreground">{index + 1}</span>
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

ReportsPage.layout = {
    breadcrumbs: [
        {
            title: 'Reports',
            href: reports(),
        },
    ],
};
