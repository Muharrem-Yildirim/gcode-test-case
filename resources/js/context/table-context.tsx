import React, { createContext, useContext, useEffect, useState } from "react";
import {
    useReactTable,
    PaginationState,
    getCoreRowModel,
} from "@tanstack/react-table";
import columns from "@/consts/columns";
import { useCurrencyQuery } from "./currency-query-context";

interface TableContextType {
    table: any;
    setPagination: (pagination: PaginationState) => void;
    pagination: PaginationState;
    data: any[];
}

const TableContext = createContext<TableContextType | undefined>(undefined);

export const TableProvider: React.FC<{ children: React.ReactNode }> = ({
    children,
}) => {
    const { data, setPagination, pagination } = useCurrencyQuery();
    const [derrivedData, setDerrivedData] = useState<{
        rows: any[];
    }>({
        rows: [],
    });

    useEffect(() => {
        setDerrivedData(data);
    }, [data]);

    const table = useReactTable({
        data: derrivedData?.rows || [],
        columns,
        rowCount: data?.length,
        state: {
            pagination,
        },
        onPaginationChange: setPagination,
        getCoreRowModel: getCoreRowModel(),
        manualPagination: true,
        debugAll: true,
    });

    return (
        <TableContext.Provider
            value={{
                table,
                setPagination,
                pagination,
                data,
            }}
        >
            {children}
        </TableContext.Provider>
    );
};

export const useTableContext = (): TableContextType => {
    const context = useContext(TableContext);
    if (!context) {
        throw new Error("useTableContext must be used within a TableProvider");
    }
    return context;
};
