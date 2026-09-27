import { Head } from '@inertiajs/react';
import { AlertTriangle, ArrowUpRight, PackageSearch } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { inventory } from '@/routes';

const currencyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

type InventoryProps = {
    totalSkus?: number;
    totalStock?: number;
    criticalItems?: number;
    inventoryValue?: number;
    stockLevels?: Array<{ name: string; qty: number; reorder: number; status: string }>;
};

export default function InventoryPage({
    totalSkus = 0,
    totalStock = 0,
    criticalItems = 0,
    inventoryValue = 0,
    stockLevels = [],
}: InventoryProps) {
    return (
        <>
            <Head title="Inventory" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
                <div className="flex flex-col gap-3 rounded-xl border border-border/70 bg-card/80 p-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Inventory</h1>
                    </div>
                    <Button variant="outline" className="gap-2">
                        <ArrowUpRight className="h-4 w-4" />
                        Reorder report
                    </Button>
                </div>

                <div className="grid gap-4 md:grid-cols-3">
                    <Card className="border-border/70 bg-card/80">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <PackageSearch className="h-4 w-4" />
                                Total SKUs
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-semibold">{totalSkus}</div>
                        </CardContent>
                    </Card>
                    <Card className="border-border/70 bg-card/80">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <AlertTriangle className="h-4 w-4 text-amber-500" />
                                Critical Items
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-semibold">{criticalItems}</div>
                        </CardContent>
                    </Card>
                    <Card className="border-border/70 bg-card/80">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2 text-sm font-medium text-muted-foreground">
                                <ArrowUpRight className="h-4 w-4 text-emerald-500" />
                                Inventory Value
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-semibold">{currencyFormatter.format(inventoryValue)}</div>
                        </CardContent>
                    </Card>
                </div>

                <Card className="border-border/70 bg-card/80">
                    <CardHeader>
                        <CardTitle>Stock levels</CardTitle>
                    </CardHeader>
                    <CardContent className="p-0">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th className="px-4 py-3 font-medium">Product</th>
                                        <th className="px-4 py-3 font-medium">Qty</th>
                                        <th className="px-4 py-3 font-medium">Reorder</th>
                                        <th className="px-4 py-3 font-medium">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {stockLevels.length > 0 ? (
                                        stockLevels.map((item) => (
                                            <tr key={item.name} className="border-t border-border/70">
                                                <td className="px-4 py-3 font-medium">{item.name}</td>
                                                <td className="px-4 py-3">{item.qty}</td>
                                                <td className="px-4 py-3">{item.reorder}</td>
                                                <td className="px-4 py-3">
                                                    <Badge
                                                        variant={
                                                            item.status === 'Healthy' || item.status === 'Out of stock'
                                                                ? 'default'
                                                                : 'secondary'
                                                        }
                                                    >
                                                        {item.status}
                                                    </Badge>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan={4} className="px-4 py-6 text-center text-muted-foreground">
                                                No inventory data yet.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

InventoryPage.layout = {
    breadcrumbs: [
        {
            title: 'Inventory',
            href: inventory(),
        },
    ],
};
