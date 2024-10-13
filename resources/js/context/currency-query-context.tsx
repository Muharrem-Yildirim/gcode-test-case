import { useToast } from "@/hooks/use-toast";
import { tableParser } from "@/lib/utils";
import {
    QueryClient,
    QueryClientProvider,
    useQuery,
} from "@tanstack/react-query";
import { PaginationState } from "@tanstack/react-table";
import axios from "axios";
import { useContext, useState, createContext, useEffect } from "react";

const CurrencyQueryContext = createContext({
    data: [],
    isLoading: false,
    isFetched: false,
    setPagination: () => {},
    pagination: {
        pageIndex: 1,
        pageSize: 10,
    },
    dateRange: {
        from: new Date(),
        to: undefined,
    },
    setDateRange: () => {},
    onSearch: () => {},
} as any);

export default CurrencyQueryContext;

const fetchData = async (
    page: PaginationState,
    dateRange: {
        from: Date;
        to: Date | undefined;
    },
    search = ""
) => {
    const { data } = await axios.get("/api/currencies", {
        params: {
            page: page.pageIndex,
            start_date: dateRange.from,
            end_date: dateRange.to,
            search,
        },
    });

    return tableParser({ data, pageSize: 10 });
};

export const useCurrencyQuery = () => {
    const context = useContext(CurrencyQueryContext);
    if (!context) {
        throw new Error(
            "useCurrencyQuery must be used within a CurrencyQueryProvider"
        );
    }
    return context;
};

const CurrencyQueryProviderNonWrapped: React.FC<{
    children: React.ReactNode;
}> = ({ children }) => {
    const [pagination, setPagination] = useState({
        pageIndex: 1,
        pageSize: 10,
    });

    const [search, setSearch] = useState("");

    const [dateRange, setDateRange] = useState<{
        from: Date;
        to: Date | undefined;
    }>({
        from: new Date(),
        to: undefined,
    });

    const { toast } = useToast();

    const { data, isLoading, isFetched } = useQuery({
        queryKey: ["currencies", pagination, dateRange, search],
        queryFn: () => fetchData(pagination, dateRange, search),
    });

    useEffect(() => {
        setPagination({
            pageIndex: 1,
            pageSize: 10,
        });

        if (dateRange.from.getDay() === 0 || dateRange.from.getDay() === 6) {
            toast({
                title: "Hata",
                description: "Haftasonuna ait kur bilgilerini veremiyoruz.",
                variant: "destructive",
            });
        }
    }, [dateRange]);

    return (
        <QueryClientProvider client={new QueryClient()}>
            <CurrencyQueryContext.Provider
                value={{
                    data,
                    isLoading,
                    isFetched,
                    setPagination,
                    pagination,
                    dateRange,
                    setDateRange,
                    onSearch: (text: string) => setSearch(text),
                }}
            >
                {children}
            </CurrencyQueryContext.Provider>
        </QueryClientProvider>
    );
};

export const CurrencyQueryProvider = ({
    children,
}: {
    children: React.ReactNode;
}) => {
    const queryClient = new QueryClient();
    return (
        <QueryClientProvider client={queryClient}>
            <CurrencyQueryProviderNonWrapped>
                {children}
            </CurrencyQueryProviderNonWrapped>
        </QueryClientProvider>
    );
};
