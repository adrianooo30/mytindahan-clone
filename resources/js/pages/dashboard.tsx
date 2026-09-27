import { Head } from '@inertiajs/react';
import {
    Activity,
    AlertTriangle,
    Boxes,
    CreditCard,
    PackageCheck,
    TrendingUp,
} from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard } from '@/routes';

const currencyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

type DashboardProps = {
    todaySales?: number;
    transactionCount?: number;
    inventoryValue?: number;
    outstandingUtang?: number;
    lowStock?: Array<{ name: string; qty: number }>;
    expiringSoon?: Array<{ name: string; date: string }>;
};

const salesChart = [48, 74, 52, 88, 63, 96, 78, 118, 94, 122, 104, 136];

export default function Dashboard({
    todaySales = 0,
    transactionCount = 0,
    inventoryValue = 0,
    outstandingUtang = 0,
    lowStock = [],
    expiringSoon = [],
}: DashboardProps) {
    const summaryCards = [
        {
            title: 'Today\'s Sales',
            value: currencyFormatter.format(todaySales),
            change: '+12.4%',
            icon: TrendingUp,
        },
        {
            title: 'Today\'s Transactions',
            value: String(transactionCount),
            change: '+18.1%',
            icon: CreditCard,
        },
        {
            title: 'Outstanding Utang',
            value: currencyFormatter.format(outstandingUtang),
            change: '-3.2%',
            icon: Activity,
        },
        {
            title: 'Inventory Value',
            value: currencyFormatter.format(inventoryValue),
            change: '+5.6%',
            icon: Boxes,
        },
    ];

    return (
        <>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4 md:p-6">
                <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    {summaryCards.map(({ title, value, change, icon: Icon }) => (
                        <Card key={title} className="border-border/70 bg-card/80">
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium text-muted-foreground">
                                    {title}
                                </CardTitle>
                                <div className="rounded-md bg-primary/10 p-2 text-primary">
                                    <Icon className="h-4 w-4" />
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-semibold tracking-tight">{value}</div>
                                <p className="mt-1 text-xs text-muted-foreground">
                                    <span className="text-emerald-500">{change}</span> vs yesterday
                                </p>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <div className="grid gap-4 xl:grid-cols-[1.6fr_0.9fr]">
                    <Card className="border-border/70 bg-card/80">
                        <CardHeader className="flex flex-row items-center justify-between">
                            <div>
                                <CardTitle>Sales Overview</CardTitle>
                                <CardDescription>This month</CardDescription>
                            </div>
                            <Button variant="outline" size="sm">
                                View report
                            </Button>
                        </CardHeader>
                        <CardContent className="pt-4">
                            <div className="flex h-52 items-end gap-3">
                                {salesChart.map((value, index) => (
                                    <div key={index} className="flex flex-1 flex-col items-center justify-end gap-2">
                                        <div
                                            className="w-full rounded-t-md bg-gradient-to-t from-primary to-primary/60"
                                            style={{ height: `${value}%` }}
                                        />
                                        <span className="text-[10px] text-muted-foreground">
                                            {index + 1}
                                        </span>
                                    </div>
                                ))}
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="border-border/70 bg-card/80">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <AlertTriangle className="h-4 w-4 text-amber-500" />
                                Low Stock
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-3">
                            {lowStock.length > 0 ? (
                                lowStock.map((item) => (
                                    <div key={item.name} className="flex items-center justify-between rounded-lg border border-border/80 px-3 py-2">
                                        <span className="font-medium">{item.name}</span>
                                        <Badge variant="secondary">{item.qty}</Badge>
                                    </div>
                                ))
                            ) : (
                                <div className="text-sm text-muted-foreground">No low-stock products.</div>
                            )}
                        </CardContent>
                    </Card>
                </div>

                <Card className="border-border/70 bg-card/80">
                    <CardHeader className="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle className="flex items-center gap-2">
                                <PackageCheck className="h-4 w-4 text-emerald-500" />
                                Expiring Soon
                            </CardTitle>
                            <CardDescription>Items nearing expiry</CardDescription>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-3">
                            {expiringSoon.length > 0 ? (
                                expiringSoon.map((item) => (
                                    <div key={`${item.name}-${item.date}`} className="flex items-center justify-between rounded-lg border border-border/80 px-3 py-2">
                                        <span className="font-medium">{item.name}</span>
                                        <span className="text-sm text-muted-foreground">{item.date}</span>
                                    </div>
                                ))
                            ) : (
                                <div className="text-sm text-muted-foreground">No expiring items.</div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
