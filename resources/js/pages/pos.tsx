import { Form, Head } from '@inertiajs/react';
import { Search, ShoppingCart } from 'lucide-react';
import { useMemo, useState } from 'react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { pos } from '@/routes';

type PosProduct = {
    id: number;
    name: string;
    price: number;
    stock: number;
};

type PosProps = {
    products?: PosProduct[];
};

const currencyFormatter = new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
});

export default function PosPage({ products = [] }: PosProps) {
    const [search, setSearch] = useState('');
    const [selectedProducts, setSelectedProducts] = useState<Record<number, number>>({});

    const filteredProducts = useMemo(
        () => products.filter((product) => product.name.toLowerCase().includes(search.toLowerCase())),
        [products, search],
    );

    const cartItems = useMemo(
        () => products
            .filter((product) => selectedProducts[product.id])
            .map((product) => ({
                ...product,
                quantity: selectedProducts[product.id],
                lineTotal: selectedProducts[product.id] * product.price,
            })),
        [products, selectedProducts],
    );

    const subtotal = cartItems.reduce((sum, item) => sum + item.lineTotal, 0);
    const discount = 0;
    const total = subtotal - discount;

    const addToCart = (productId: number) => {
        setSelectedProducts((current) => ({
            ...current,
            [productId]: (current[productId] ?? 0) + 1,
        }));
    };

    const decrementItem = (productId: number) => {
        setSelectedProducts((current) => {
            const next = (current[productId] ?? 0) - 1;

            if (next <= 0) {
                const { [productId]: _, ...rest } = current;
                return rest;
            }

            return { ...current, [productId]: next };
        });
    };

    return (
        <>
            <Head title="POS" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
                <div className="rounded-xl border border-border/70 bg-card/80 p-4">
                    <h1 className="text-2xl font-semibold tracking-tight">POS</h1>
                </div>

                <div className="grid gap-4 xl:grid-cols-[1.3fr_0.9fr]">
                    <Card className="border-border/70 bg-card/80">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <Search className="h-4 w-4" />
                                Search products
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <Input placeholder="Search products..." value={search} onChange={(event) => setSearch(event.target.value)} />
                            <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                {filteredProducts.length > 0 ? (
                                    filteredProducts.map((product) => (
                                        <button
                                            key={product.id}
                                            type="button"
                                            onClick={() => addToCart(product.id)}
                                            className="rounded-xl border border-border/80 bg-muted/30 p-3 text-left transition hover:border-primary/40 hover:bg-primary/5"
                                        >
                                            <div className="flex items-center justify-between gap-2">
                                                <span className="font-medium">{product.name}</span>
                                                <Badge variant="secondary">{currencyFormatter.format(product.price)}</Badge>
                                            </div>
                                            <div className="mt-2 text-xs text-muted-foreground">Stock: {product.stock}</div>
                                        </button>
                                    ))
                                ) : (
                                    <div className="col-span-full text-sm text-muted-foreground">No products found.</div>
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="border-border/70 bg-card/80">
                        <CardHeader className="flex flex-row items-center justify-between">
                            <CardTitle className="flex items-center gap-2">
                                <ShoppingCart className="h-4 w-4" />
                                Cart
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div className="space-y-3">
                                {cartItems.length > 0 ? (
                                    cartItems.map((item) => (
                                        <div key={item.id} className="flex items-center justify-between gap-3 rounded-lg border border-border/80 p-3">
                                            <div>
                                                <div className="font-medium">{item.name}</div>
                                                <div className="text-sm text-muted-foreground">{currencyFormatter.format(item.price)} each</div>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                <Button variant="outline" size="icon" aria-label={`Decrease ${item.name}`} onClick={() => decrementItem(item.id)}>
                                                    -
                                                </Button>
                                                <span className="w-6 text-center font-medium">{item.quantity}</span>
                                                <Button variant="outline" size="icon" aria-label={`Increase ${item.name}`} onClick={() => addToCart(item.id)}>
                                                    +
                                                </Button>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="text-sm text-muted-foreground">Cart is empty.</div>
                                )}
                            </div>

                            <div className="space-y-2 border-t border-border/80 pt-4 text-sm">
                                <div className="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>{currencyFormatter.format(subtotal)}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span>Discount</span>
                                    <span>{currencyFormatter.format(discount)}</span>
                                </div>
                                <div className="flex justify-between text-base font-semibold">
                                    <span>Total</span>
                                    <span>{currencyFormatter.format(total)}</span>
                                </div>
                            </div>

                            <Form action={pos().url} method="post" className="space-y-4">
                                {cartItems.map((item) => (
                                    <input key={item.id} type="hidden" name="items[][product_id]" value={item.id} />
                                ))}
                                {cartItems.map((item) => (
                                    <input key={`${item.id}-qty`} type="hidden" name="items[][quantity]" value={item.quantity} />
                                ))}
                                {cartItems.map((item) => (
                                    <input key={`${item.id}-price`} type="hidden" name="items[][unit_price]" value={item.price} />
                                ))}
                                <input type="hidden" name="discount" value={discount} />
                                <input type="hidden" name="payment_method" value="cash" />
                                <div className="space-y-2">
                                    <Label htmlFor="amount_paid">Amount paid</Label>
                                    <Input id="amount_paid" name="amount_paid" type="number" min="0" step="1" defaultValue={total} />
                                </div>
                                <div className="grid grid-cols-3 gap-2">
                                    <Button type="button" variant="outline">Cash</Button>
                                    <Button type="button" variant="outline">GCash</Button>
                                    <Button type="button" variant="outline">Utang</Button>
                                </div>
                                <Button type="submit" className="w-full" disabled={cartItems.length === 0}>
                                    Complete sale
                                </Button>
                            </Form>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}

PosPage.layout = {
    breadcrumbs: [
        {
            title: 'POS',
            href: pos(),
        },
    ],
};
