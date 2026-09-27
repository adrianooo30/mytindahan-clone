import { Form, Head } from '@inertiajs/react';
import StoreController from '@/actions/App/Http/Controllers/Settings/StoreController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { edit } from '@/routes/store';

type StorePageProps = {
    store: {
        id: number;
        name: string;
        currency: string;
        phone?: string | null;
        address?: string | null;
    };
};

export default function StoreSettings({ store }: StorePageProps) {
    return (
        <>
            <Head title="Store settings" />

            <h1 className="sr-only">Store settings</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Store information"
                    description="Update your store name, contact details, and address"
                />

                <Form
                    {...StoreController.update.form()}
                    options={{ preserveScroll: true }}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Store name</Label>
                                <Input
                                    id="name"
                                    defaultValue={store.name}
                                    name="name"
                                    required
                                    placeholder="Store name"
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="currency">Currency</Label>
                                <Input
                                    id="currency"
                                    defaultValue={store.currency}
                                    name="currency"
                                    required
                                    maxLength={3}
                                    placeholder="PHP"
                                />
                                <InputError message={errors.currency} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="phone">Phone</Label>
                                <Input
                                    id="phone"
                                    defaultValue={store.phone ?? ''}
                                    name="phone"
                                    placeholder="0917 123 4567"
                                />
                                <InputError message={errors.phone} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="address">Address</Label>
                                <Textarea
                                    id="address"
                                    defaultValue={store.address ?? ''}
                                    name="address"
                                    placeholder="Store address"
                                    rows={4}
                                />
                                <InputError message={errors.address} />
                            </div>

                            <div className="flex items-center gap-4">
                                <Button disabled={processing}>Save</Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

StoreSettings.layout = {
    breadcrumbs: [
        {
            title: 'Store settings',
            href: edit(),
        },
    ],
};
