import { ChevronLeftIcon, ChevronRightIcon } from "@radix-ui/react-icons";
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from "@/components/ui/pagination";
import { cn } from "@/lib/utils";
import type { PaginationState } from "@tanstack/react-table";

const CurrenciesDatatablePaginator = ({
    data,
    pagination,
    table,
}: {
    data: any;
    pagination: PaginationState;
    table: any;
}) => {
    const prevPage =
        pagination.pageIndex === 2
            ? 0
            : pagination.pageIndex - 1 < 0
            ? 0
            : pagination.pageIndex - 1;
    const nextPage =
        pagination.pageIndex === 0
            ? pagination.pageIndex + 2
            : pagination.pageIndex + 1;

    return (
        <Pagination className="flex items-center justify-end space-x-2 py-4">
            <PaginationContent>
                <PaginationItem className="mx-4">
                    <PaginationPrevious
                        onClick={() => {
                            if (prevPage === 2) return;
                            table.setPageIndex(prevPage);
                        }}
                        className={cn("px-4 cursor-pointer", {
                            "cursor-not-allowed": nextPage === 2,
                        })}
                    >
                        <ChevronLeftIcon className="size-4" />
                    </PaginationPrevious>
                </PaginationItem>

                {data?.elements.map((element: any, index: number) => {
                    if (element === "...") {
                        return (
                            <PaginationEllipsis key={index}>
                                <span>...</span>
                            </PaginationEllipsis>
                        );
                    }

                    return (
                        <PaginationItem key={index}>
                            <PaginationLink
                                className="size-9 p-0 cursor-pointer"
                                isActive={
                                    element ===
                                    (pagination.pageIndex === 0
                                        ? pagination.pageIndex + 1
                                        : pagination.pageIndex)
                                }
                                onClick={() => {
                                    table.setPageIndex(element);
                                }}
                            >
                                {element}
                            </PaginationLink>
                        </PaginationItem>
                    );
                })}

                <PaginationItem className="mx-4">
                    <PaginationNext
                        onClick={() => {
                            if (nextPage > data?.meta.last_page) return;

                            table.setPageIndex(nextPage);
                        }}
                        className={cn("p-4 cursor-pointer", {
                            "cursor-not-allowed":
                                nextPage > data?.meta.last_page,
                        })}
                    >
                        <ChevronRightIcon className="size-4" />
                    </PaginationNext>
                </PaginationItem>
            </PaginationContent>
        </Pagination>
    );
};

export default CurrenciesDatatablePaginator;
