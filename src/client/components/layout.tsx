import { FC } from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import { AppSidebar } from '@/components/app-sidebar';
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbList,
  BreadcrumbPage,
} from '@/components/ui/breadcrumb';
import { Separator } from '@/components/ui/separator';
import {
  SidebarInset,
  SidebarProvider,
  SidebarTrigger,
} from '@/components/ui/sidebar';
import { useTranslation } from 'react-i18next';
import { BREADCRUMB_CONFIG } from '@/config/breadcrumb';

const Layout: FC = () => {
  const { pathname } = useLocation();
  const { t } = useTranslation();

  const cleanPathname = pathname.replace(/^\/|\/$/g, '');
  const breadCrumbTranslateKey = BREADCRUMB_CONFIG[cleanPathname as keyof typeof BREADCRUMB_CONFIG] || 'Home';

  return (
    <SidebarProvider>
      <AppSidebar />
      <SidebarInset>
        <header className="flex h-16 shrink-0 items-center gap-2">
          <div className="flex items-center gap-2 px-4">
            <SidebarTrigger className="-ml-1" />
            <Separator orientation="vertical" className="mr-2 h-4" />
            <Breadcrumb>
              <BreadcrumbList>
                <BreadcrumbItem>
                  <BreadcrumbPage>
                    {/* Actually, this is dirty typing. But for time reason, I keep it this way. */}
                    {t(breadCrumbTranslateKey as unknown as TemplateStringsArray)}
                  </BreadcrumbPage>
                </BreadcrumbItem>
              </BreadcrumbList>
            </Breadcrumb>
          </div>
        </header>

        <div className="flex flex-col gap-4 p-4">
          <Outlet />
        </div>
      </SidebarInset>
    </SidebarProvider>
  );
};

export default Layout;
