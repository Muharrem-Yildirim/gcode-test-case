import { DatePickerWithRange } from "@/components/datepicker";
import { Input } from "@/components/ui/input";
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Button } from "@/components/ui/button";
import { ChevronDown } from "lucide-react";
import { useTableContext } from "@/context/table-context";
import { useEffect, useState } from "react";
import { useCurrencyQuery } from "@/context/currency-query-context";

const Header = () => {
    return (
        <div className="grid grid-cols-1 md:grid-cols-2 mb-6 gap-4">
            <FilterSection />
            <SettingsSection />
        </div>
    );
};
export default Header;

const FilterSection = () => {
    const [input, setInput] = useState("");
    const { onSearch } = useCurrencyQuery();

    useEffect(() => {
        if (input.replace(" ", "") === "") onSearch("");
    }, [input]);

    return (
        <div className="flex flex-col">
            <div className="mb-3">
                <h2 className="text-xl mb-4">Filtre</h2>
                <DatePickerWithRange />
            </div>
            <div className="flex flex-col ">
                <h2 className="text-xl mb-3">Arama</h2>
                <Input
                    className="border rounded-md lg:w-[300px] w-full"
                    placeholder="Ara"
                    value={input}
                    onChange={(e) => setInput(e.target.value)}
                    onKeyUp={(e) => {
                        if (e.key === "Enter") onSearch(input);
                    }}
                />
            </div>
        </div>
    );
};

const SettingsSection = () => {
    const { table } = useTableContext();

    return (
        <div className="ml-auto flex gap-4 flex-col">
            <div className="flex flex-col lg:items-end ">
                <h2 className="text-xl mb-3 text-end">Ayarlar</h2>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="outline">
                            Sütunlar <ChevronDown className="ml-2 h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        {table
                            .getAllColumns()
                            .filter((column: any) => column.getCanHide())
                            .map((column: any) => {
                                return (
                                    <DropdownMenuCheckboxItem
                                        key={column.id}
                                        className="capitalize"
                                        checked={column.getIsVisible()}
                                        onCheckedChange={(value) =>
                                            column.toggleVisibility(!!value)
                                        }
                                    >
                                        {column.columnDef.localizedHeader}
                                    </DropdownMenuCheckboxItem>
                                );
                            })}
                    </DropdownMenuContent>
                </DropdownMenu>
            </div>
        </div>
    );
};
