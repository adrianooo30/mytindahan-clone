import { Link } from '@inertiajs/react';
import {
    BarChart3,
    BellRing,
    BookOpen,
    FolderGit2,
    LayoutGrid,
    Package,
    PackageSearch,
    ReceiptText,
    ShoppingCart,
    Users,
    Zap,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { bills, customers, dashboard, eload, inventory, notifications, pos, products, reports } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Products',
        href: products(),
        icon: Package,
    },
    {
        title: 'Inventory',
        href: inventory(),
        icon: PackageSearch,
    },
    {
        title: 'Customers',
        href: customers(),
        icon: Users,
    },
    {
        title: 'Reports',
        href: reports(),
        icon: BarChart3,
    },
    {
        title: 'Alerts',
        href: notifications(),
        icon: BellRing,
    },
    {
        title: 'E-load',
        href: eload(),
        icon: Zap,
    },
    {
        title: 'Bills',
        href: bills(),
        icon: ReceiptText,
    },
    {
        title: 'POS',
        href: pos(),
        icon: ShoppingCart,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
