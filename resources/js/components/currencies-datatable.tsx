import { flexRender } from "@tanstack/react-table";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import CurrenciesDatatablePaginator from "./currencies-datatable-paginator";
import columns from "@/consts/columns";
import { useCurrencyQuery } from "@/context/currency-query-context";
import { useTableContext } from "@/context/table-context";

function Datatable() {
    const { table, data, pagination } = useTableContext();
    const { isLoading } = useCurrencyQuery();

    return (
        <div className="w-full">
            <div className="rounded-md border">
                <Table>
                    <TableHeader>
                        {table.getHeaderGroups().map((headerGroup: any) => (
                            <TableRow key={headerGroup.id} className="!text-xs">
                                {headerGroup.headers.map((header: any) => {
                                    return (
                                        <TableHead
                                            key={header.id}
                                            className="h-20 md:h-12"
                                        >
                                            {header.isPlaceholder
                                                ? null
                                                : flexRender(
                                                      header.column.columnDef
                                                          .header,
                                                      header.getContext()
                                                  )}
                                        </TableHead>
                                    );
                                })}
                            </TableRow>
                        ))}
                    </TableHeader>
                    <TableBody className="font-bold text-muted-foreground">
                        {table.getRowModel().rows?.length ? (
                            table.getRowModel().rows.map((row: any) => (
                                <TableRow
                                    key={row.id}
                                    data-state={
                                        row.getIsSelected() && "selected"
                                    }
                                >
                                    {row.getVisibleCells().map((cell: any) => (
                                        <TableCell
                                            key={cell.id}
                                            className="!text-xs"
                                        >
                                            {flexRender(
                                                cell.column.columnDef.cell,
                                                cell.getContext()
                                            )}
                                        </TableCell>
                                    ))}
                                </TableRow>
                            ))
                        ) : isLoading ? (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center"
                                >
                                    Yükleniyor..
                                </TableCell>
                            </TableRow>
                        ) : (
                            <TableRow>
                                <TableCell
                                    colSpan={columns.length}
                                    className="h-24 text-center"
                                >
                                    Veri yok.
                                </TableCell>
                            </TableRow>
                        )}
                    </TableBody>
                </Table>
            </div>
            <div>
                <CurrenciesDatatablePaginator
                    data={data}
                    pagination={pagination}
                    table={table}
                />
            </div>
        </div>
    );
}

export const CurrenciesDatatable = () => {
    const queryClient = new QueryClient();

    return (
        <QueryClientProvider client={queryClient}>
            <Datatable />
        </QueryClientProvider>
    );
};
