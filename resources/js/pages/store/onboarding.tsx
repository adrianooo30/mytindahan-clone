import { Form, Head } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { store as storeRoute } from '@/routes/store/onboarding';

type Props = {
    store: {
        name: string;
        currency: string;
        phone?: string | null;
        address?: string | null;
    };
};

export default function StoreOnboarding({ store: storeData }: Props) {
    return (
        <>
            <Head title="Set up your store" />

            <div className="mx-auto max-w-2xl py-12">
                <div className="rounded-3xl border border-border bg-card p-8 shadow-sm">
                    <div className="mb-8 space-y-2">
                        <p className="text-sm font-medium uppercase tracking-[0.2em] text-primary">
                            Welcome
                        </p>
                        <h1 className="text-3xl font-bold tracking-tight">
                            Set up your first store
                        </h1>
                        <p className="text-muted-foreground">
                            Add your business details to start tracking sales, inventory, and customer balances.
                        </p>
                    </div>

                    <Form
                        {...storeRoute.form()}
                        className="space-y-6"
                    >
                        {({ processing, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Store name</Label>
                                    <Input
                                        id="name"
                                        name="name"
                                        defaultValue={storeData.name}
                                        autoFocus
                                        placeholder="My Tindahan"
                                    />
                                    {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="currency">Currency</Label>
                                    <Input
                                        id="currency"
                                        name="currency"
                                        defaultValue={storeData.currency}
                                        maxLength={3}
                                        placeholder="PHP"
                                    />
                                    {errors.currency && <p className="text-sm text-destructive">{errors.currency}</p>}
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="phone">Phone</Label>
                                    <Input
                                        id="phone"
                                        name="phone"
                                        defaultValue={storeData.phone ?? ''}
                                        placeholder="0917 123 4567"
                                    />
                                    {errors.phone && <p className="text-sm text-destructive">{errors.phone}</p>}
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="address">Address</Label>
                                    <Textarea
                                        id="address"
                                        name="address"
                                        defaultValue={storeData.address ?? ''}
                                        rows={4}
                                        placeholder="Store address"
                                    />
                                    {errors.address && <p className="text-sm text-destructive">{errors.address}</p>}
                                </div>

                                <Button type="submit" className="w-full" disabled={processing}>
                                    {processing ? 'Creating store...' : 'Create store'}
                                    <ArrowRight className="ml-2 h-4 w-4" />
                                </Button>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </>
    );
}
