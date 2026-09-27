import { Head, Link, usePage } from '@inertiajs/react';
import {
    ArrowRight,
    BadgeCheck,
    BarChart3,
    CreditCard,
    Package,
    ShieldCheck,
    ShoppingCart,
    Star,
    TrendingUp,
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard, login, register } from '@/routes';

const features = [
    {
        title: 'Fast POS checkout',
        description: 'Process sales quickly, record payments, and keep the queue moving.',
        icon: ShoppingCart,
    },
    {
        title: 'Smart inventory tracking',
        description: 'Monitor stock levels and expiration dates before they become losses.',
        icon: Package,
    },
    {
        title: 'UTANG and payments',
        description: 'Track customer balances, ledger entries, and payment runs in one place.',
        icon: CreditCard,
    },
    {
        title: 'Live business insights',
        description: 'See sales performance and store health with instant reporting dashboards.',
        icon: BarChart3,
    },
];

const plans = [
    {
        name: 'Starter',
        price: '₱999',
        description: 'For a single neighborhood store setup.',
        features: ['1 store', 'POS dashboard', 'Inventory tracking', 'Basic reports'],
        highlighted: false,
    },
    {
        name: 'Growth',
        price: '₱1,999',
        description: 'For stores ready to manage more volume and customers.',
        features: ['Multi-store access', 'Advanced reports', 'Purchase tracking', 'Priority support'],
        highlighted: true,
    },
];

const testimonials = [
    {
        quote: 'The System cut our counting time in half and made it easier to monitor customer credit.',
        author: 'Liza, Sari-sari owner',
    },
    {
        quote: 'We finally have a clear picture of sales, expenses, and top-selling items every week.',
        author: 'Ramon, convenience store owner',
    },
];

export default function Welcome() {
    const { auth } = usePage().props;

    return (
        <>
            <Head title="MyTindahan Clone" />
            <div className="min-h-screen bg-background text-foreground">
                <header className="border-b border-border/60 bg-background/90 backdrop-blur-sm">
                    <div className="mx-auto flex max-w-6xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                        <div className="flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-lg font-bold text-primary-foreground">
                                M
                            </div>
                            <div>
                                <div className="text-lg font-semibold">MyTindahan</div>
                            </div>
                        </div>
                        <nav className="hidden items-center gap-6 text-sm text-muted-foreground md:flex">
                            <a href="#features">Features</a>
                            <a href="#pricing">Pricing</a>
                            <a href="#how-it-works">How it works</a>
                            <a href="#testimonials">Testimonials</a>
                        </nav>
                        <div className="flex items-center gap-2">
                            {auth.user ? (
                                <Link href={dashboard()}>
                                    <Button>Go to dashboard</Button>
                                </Link>
                            ) : (
                                <>
                                    <Link href={login()}>
                                        <Button variant="outline">Log in</Button>
                                    </Link>
                                    <Link href={register()}>
                                        <Button>Register</Button>
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </header>

                <main>
                    <section className="mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8">
                        <div className="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                            <div>
                                <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-primary/30 bg-primary/5 px-3 py-1 text-sm text-primary">
                                    <BadgeCheck className="h-4 w-4" />
                                    Built for sari-sari stores and mini shops
                                </div>
                                <h1 className="max-w-xl text-4xl font-bold tracking-tight sm:text-5xl">
                                    Run your store from one simple, reliable dashboard.
                                </h1>
                                <p className="mt-5 max-w-xl text-lg text-muted-foreground">
                                    Manage sales, inventory, customer credit, expenses, and store performance with a platform designed for everyday retail operations.
                                </p>
                                <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                                    <Link href={register()}>
                                        <Button size="lg" className="gap-2">
                                            Start free trial
                                            <ArrowRight className="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Link href={login()}>
                                        <Button variant="outline" size="lg">
                                            View demo
                                        </Button>
                                    </Link>
                                </div>
                                <div className="mt-8 flex items-center gap-6 text-sm text-muted-foreground">
                                    <div className="flex items-center gap-2">
                                        <ShieldCheck className="h-4 w-4 text-emerald-500" />
                                        Secure and private
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <TrendingUp className="h-4 w-4 text-emerald-500" />
                                        Performance minded
                                    </div>
                                </div>
                            </div>

                            <div className="rounded-3xl border border-border/80 bg-card p-5 shadow-lg shadow-primary/5">
                                <div className="rounded-2xl border border-border/80 bg-gradient-to-br from-primary/10 via-card to-card p-5">
                                    <div className="mb-6 flex items-center justify-between">
                                        <div>
                                            <p className="text-sm text-muted-foreground">Today</p>
                                            <h2 className="text-3xl font-bold">₱8,450</h2>
                                        </div>
                                        <div className="rounded-xl bg-emerald-500/10 p-3 text-emerald-600">
                                            <TrendingUp className="h-6 w-6" />
                                        </div>
                                    </div>

                                    <div className="space-y-4">
                                        <div className="flex items-center justify-between rounded-xl border border-border/80 bg-background/80 p-3">
                                            <span className="text-sm text-muted-foreground">Sales</span>
                                            <span className="font-semibold">₱8,450</span>
                                        </div>
                                        <div className="flex items-center justify-between rounded-xl border border-border/80 bg-background/80 p-3">
                                            <span className="text-sm text-muted-foreground">Transactions</span>
                                            <span className="font-semibold">124</span>
                                        </div>
                                        <div className="flex items-center justify-between rounded-xl border border-border/80 bg-background/80 p-3">
                                            <span className="text-sm text-muted-foreground">Outstanding utang</span>
                                            <span className="font-semibold">₱23,400</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="features" className="border-y border-border/60 bg-muted/20">
                        <div className="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
                            <div className="mb-12 text-center">
                                <p className="text-sm font-medium uppercase tracking-[0.2em] text-primary">Everything your store needs</p>
                                <h2 className="mt-4 text-3xl font-bold sm:text-4xl">Built for real retail operations</h2>
                            </div>
                            <div className="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                                {features.map(({ title, description, icon: Icon }) => (
                                    <Card key={title} className="border-border/80 bg-card">
                                        <CardHeader>
                                            <div className="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                <Icon className="h-5 w-5" />
                                            </div>
                                            <CardTitle>{title}</CardTitle>
                                        </CardHeader>
                                        <CardContent>
                                            <CardDescription>{description}</CardDescription>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        </div>
                    </section>

                    <section id="how-it-works" className="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
                        <div className="mb-12 text-center">
                            <p className="text-sm font-medium uppercase tracking-[0.2em] text-primary">How it works</p>
                            <h2 className="mt-4 text-3xl font-bold sm:text-4xl">A simple flow that keeps daily work moving</h2>
                        </div>
                        <div className="grid gap-6 md:grid-cols-3">
                            {[
                                'Create your store profile and register products.',
                                'Process purchases quickly from the POS and track sales.',
                                'Review inventory, expenses, and reports as your business grows.',
                            ].map((step, index) => (
                                <Card key={step} className="border-border/80 bg-card">
                                    <CardHeader>
                                        <div className="text-sm font-semibold text-primary">0{index + 1}</div>
                                        <CardTitle>{index === 0 ? 'Set up' : index === 1 ? 'Sell' : 'Grow'}</CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <CardDescription>{step}</CardDescription>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    </section>

                    <section id="pricing" className="border-y border-border/60 bg-muted/20">
                        <div className="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
                            <div className="mb-12 text-center">
                                <p className="text-sm font-medium uppercase tracking-[0.2em] text-primary">Pricing</p>
                                <h2 className="mt-4 text-3xl font-bold sm:text-4xl">Simple plans for growing stores</h2>
                            </div>
                            <div className="grid gap-6 md:grid-cols-2">
                                {plans.map((plan) => (
                                    <Card
                                        key={plan.name}
                                        className={plan.highlighted ? 'border-primary bg-primary/5 shadow-lg shadow-primary/10' : 'border-border/80 bg-card'}
                                    >
                                        <CardHeader>
                                            <CardTitle>{plan.name}</CardTitle>
                                            <div className="mt-4 text-4xl font-bold">{plan.price}<span className="text-base font-medium text-muted-foreground">/month</span></div>
                                            <CardDescription>{plan.description}</CardDescription>
                                        </CardHeader>
                                        <CardContent className="space-y-3">
                                            {plan.features.map((feature) => (
                                                <div key={feature} className="flex items-center gap-2 text-sm">
                                                    <BadgeCheck className="h-4 w-4 text-emerald-500" />
                                                    {feature}
                                                </div>
                                            ))}
                                            <Link href={register()} className="mt-4 block">
                                                <Button className="w-full">Choose plan</Button>
                                            </Link>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        </div>
                    </section>

                    <section id="testimonials" className="mx-auto max-w-6xl px-4 py-20 sm:px-6 lg:px-8">
                        <div className="mb-12 text-center">
                            <p className="text-sm font-medium uppercase tracking-[0.2em] text-primary">Testimonials</p>
                            <h2 className="mt-4 text-3xl font-bold sm:text-4xl">Store owners trust the workflow</h2>
                        </div>
                        <div className="grid gap-6 md:grid-cols-2">
                            {testimonials.map((item) => (
                                <Card key={item.author} className="border-border/80 bg-card">
                                    <CardContent className="pt-6">
                                        <div className="mb-4 flex gap-1 text-amber-400">
                                            {Array.from({ length: 5 }).map((_, index) => (
                                                <Star key={index} className="h-4 w-4 fill-current" />
                                            ))}
                                        </div>
                                        <p className="text-lg text-foreground">“{item.quote}”</p>
                                        <div className="mt-6 font-medium text-muted-foreground">{item.author}</div>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    </section>
                </main>
            </div>
        </>
    );
}
