import { Form, Head } from '@inertiajs/react';
import { MoreHorizontal, Plus, Search } from 'lucide-react';
import { useMemo, useState } from 'react';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { products } from '@/routes';

type ProductRecord = {
    id: number;
    name: string;
    sku: string | null;
    stock: number;
    price: number;
    status: string;
};

const currencyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

export default function ProductsPage({ products: productList = [] }: { products: ProductRecord[] }) {
    const [searchTerm, setSearchTerm] = useState('');

    const filteredProducts = useMemo(
        () =>
            productList.filter((product) => {
                const query = searchTerm.trim().toLowerCase();

                if (!query) {
                    return true;
                }

                return product.name.toLowerCase().includes(query)
                    || (product.sku ?? '').toLowerCase().includes(query);
            }),
        [productList, searchTerm],
    );

    return (
        <>
            <Head title="Products" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
                <div className="flex flex-col gap-4 rounded-xl border border-border/70 bg-card/80 p-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold tracking-tight">Products</h1>
                    </div>
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <div className="relative min-w-[220px]">
                            <Search className="absolute top-2.5 left-2.5 h-4 w-4 text-muted-foreground" />
                            <Input
                                className="pl-8"
                                placeholder="Search products..."
                                value={searchTerm}
                                onChange={(event) => setSearchTerm(event.target.value)}
                            />
                        </div>
                        <Button variant="outline">Category</Button>
                        <Dialog>
                            <DialogTrigger asChild>
                                <Button className="gap-2">
                                    <Plus className="h-4 w-4" />
                                    Add Product
                                </Button>
                            </DialogTrigger>
                            <DialogContent className="sm:max-w-xl">
                                <DialogHeader>
                                    <DialogTitle>Add product</DialogTitle>
                                </DialogHeader>
                                <Form action={products().url} method="post" className="space-y-4">
                                    {({ processing, errors }) => (
                                        <>
                                            <div className="grid gap-4 md:grid-cols-2">
                                                <div className="space-y-2 md:col-span-2">
                                                    <Label htmlFor="name">Product name</Label>
                                                    <Input id="name" name="name" required />
                                                    <InputError message={errors.name} />
                                                </div>

                                                <div className="space-y-2">
                                                    <Label htmlFor="sku">SKU</Label>
                                                    <Input id="sku" name="sku" />
                                                    <InputError message={errors.sku} />
                                                </div>

                                                <div className="space-y-2">
                                                    <Label htmlFor="unit">Unit</Label>
                                                    <Input id="unit" name="unit" placeholder="pack" />
                                                    <InputError message={errors.unit} />
                                                </div>

                                                <div className="space-y-2">
                                                    <Label htmlFor="cost_price">Cost price</Label>
                                                    <Input id="cost_price" name="cost_price" type="number" min="0" step="1" required />
                                                    <InputError message={errors.cost_price} />
                                                </div>

                                                <div className="space-y-2">
                                                    <Label htmlFor="selling_price">Selling price</Label>
                                                    <Input id="selling_price" name="selling_price" type="number" min="0" step="1" required />
                                                    <InputError message={errors.selling_price} />
                                                </div>

                                                <div className="space-y-2 md:col-span-2">
                                                    <Label htmlFor="description">Description</Label>
                                                    <textarea
                                                        id="description"
                                                        name="description"
                                                        className="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                    />
                                                    <InputError message={errors.description} />
                                                </div>
                                            </div>

                                            <DialogFooter>
                                                <Button type="submit" disabled={processing}>
                                                    {processing ? 'Saving...' : 'Save product'}
                                                </Button>
                                            </DialogFooter>
                                        </>
                                    )}
                                </Form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>

                <Card className="border-border/70 bg-card/80">
                    <CardHeader className="flex flex-row items-center justify-between">
                        <CardTitle>Inventory</CardTitle>
                        <Button variant="ghost" size="icon" aria-label="More options">
                            <MoreHorizontal className="h-4 w-4" />
                        </Button>
                    </CardHeader>
                    <CardContent className="p-0">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm">
                                <thead className="bg-muted/50 text-muted-foreground">
                                    <tr>
                                        <th className="px-4 py-3 font-medium">Product</th>
                                        <th className="px-4 py-3 font-medium">SKU</th>
                                        <th className="px-4 py-3 font-medium">Stock</th>
                                        <th className="px-4 py-3 font-medium">Price</th>
                                        <th className="px-4 py-3 font-medium">Status</th>
                                        <th className="px-4 py-3 font-medium text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {filteredProducts.length > 0 ? (
                                        filteredProducts.map((product) => (
                                            <tr key={product.id} className="border-t border-border/70">
                                                <td className="px-4 py-3 font-medium">{product.name}</td>
                                                <td className="px-4 py-3 text-muted-foreground">{product.sku ?? '—'}</td>
                                                <td className="px-4 py-3">{product.stock}</td>
                                                <td className="px-4 py-3">{currencyFormatter.format(product.price)}</td>
                                                <td className="px-4 py-3">
                                                    <Badge variant={product.status === 'Inactive' ? 'secondary' : 'default'}>
                                                        {product.status}
                                                    </Badge>
                                                </td>
                                                <td className="px-4 py-3 text-right">
                                                    <span className="text-muted-foreground">View</span>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan={6} className="px-4 py-6 text-center text-muted-foreground">
                                                No products found.
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

ProductsPage.layout = {
    breadcrumbs: [
        {
            title: 'Products',
            href: products(),
        },
    ],
};
