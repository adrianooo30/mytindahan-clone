import { Head } from '@inertiajs/react';
import { BellRing, TriangleAlert } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { notifications } from '@/routes';

type AlertItem = {
    type: string;
    title: string;
    message: string;
};

type NotificationsProps = {
    alerts?: AlertItem[];
};

export default function NotificationsPage({ alerts = [] }: NotificationsProps) {
    return (
        <>
            <Head title="Notifications" />
            <div className="flex h-full flex-1 flex-col gap-4 p-4 md:p-6">
                <div className="rounded-xl border border-border/70 bg-card/80 p-4">
                    <h1 className="text-2xl font-semibold tracking-tight">Notifications</h1>
                </div>

                <Card className="border-border/70 bg-card/80">
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <BellRing className="h-4 w-4 text-primary" />
                            Store alerts
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-3">
                            {alerts.length > 0 ? (
                                alerts.map((alert, index) => (
                                    <div key={`${alert.type}-${index}`} className="flex items-start gap-3 rounded-lg border border-border/80 p-3">
                                        <TriangleAlert className="mt-0.5 h-4 w-4 text-amber-500" />
                                        <div>
                                            <div className="font-medium">{alert.title}</div>
                                            <div className="text-sm text-muted-foreground">{alert.message}</div>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div className="text-sm text-muted-foreground">No active alerts.</div>
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

NotificationsPage.layout = {
    breadcrumbs: [
        {
            title: 'Notifications',
            href: notifications(),
        },
    ],
};
