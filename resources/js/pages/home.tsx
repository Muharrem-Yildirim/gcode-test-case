import { CurrenciesDatatable } from "@/components/currencies-datatable";
import { Head } from "@inertiajs/react";
import { TableProvider } from "@/context/table-context";
import { CurrencyQueryProvider } from "@/context/currency-query-context";
import Header from "@/components/pages/main/header";
import { Toaster } from "@/components/ui/toaster";

export default function Home() {
    return (
        <>
            <Head title="Home" />
            <Toaster />
            <Main />
        </>
    );
}

const Main = () => {
    return (
        <CurrencyQueryProvider>
            <TableProvider>
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-10 px-4">
                    <Header />
                    <CurrenciesDatatable />
                </div>
            </TableProvider>
        </CurrencyQueryProvider>
    );
};
