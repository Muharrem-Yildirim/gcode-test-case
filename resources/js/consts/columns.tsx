import { ColumnDef } from "@tanstack/react-table";

export type Currency = {
    id: string;
    code: string;
    unit: number;
    name: string;
    cross_order: number;
    forex: {
        buying: number;
        selling: number;
    };
    banknote: {
        buying: number;
        selling: number;
    };
    cross_rate: {
        usd: number;
        other: number;
    };
    date: Date;
    created_at: Date;
    updated_at: Date;
};

export type CustomColumnDef<TData> = ColumnDef<TData> & {
    localizedHeader: string;
};

const columns: CustomColumnDef<Currency>[] = [
    {
        id: "code",
        accessorKey: "code",
        localizedHeader: "Döviz Kodu",
        header: () => (
            <div className="flex flex-col">
                <span className="!leading-3 text-2xs font-bold text-gray-900">
                    Döviz Kodu
                </span>
                <span className="!leading-3 !text-xs">Currency Code</span>
            </div>
        ),
        cell: (info) => {
            const code1 = info.getValue() as string;
            const code2 = "TRY";

            return (
                <div className="!leading-3 flex items-center gap-1">
                    {info.getValue() !== "XDR" && (
                        <img
                            src={`http://www.tcmb.gov.tr/kurlar/kurlar_tr_dosyalar/images/${info.getValue()}.gif`}
                            alt={info.getValue() as string}
                            className="w-4 h-4"
                        />
                    )}
                    {code1}/{code2}
                </div>
            );
        },
    },
    {
        id: "unit",
        accessorKey: "unit",
        localizedHeader: "Birim",
        header: () => (
            <div className="flex flex-col">
                <span className="!leading-3 text-2xs font-bold text-gray-900">
                    Birim
                </span>
                <span className="!leading-3 !text-xs">Unit</span>
            </div>
        ),
        cell: (info) => info.getValue(),
    },
    {
        id: "name",
        accessorKey: "name",
        localizedHeader: "Döviz Cinsi",
        header: () => (
            <div className="flex flex-col">
                <span className="!leading-3 text-2xs font-bold text-gray-900">
                    Döviz Cinsi
                </span>
                <span className="!leading-3 !text-xs">Currency</span>
            </div>
        ),
        cell: (info) => info.getValue(),
    },
    {
        id: "forex_buying",
        accessorKey: "forex_buying",
        localizedHeader: "Döviz Alış",
        header: () => (
            <div className="flex flex-col">
                <span className="!leading-3 text-2xs font-bold text-gray-900">
                    Döviz Alış
                </span>
                <span className="!leading-3 !text-xs">Forex Buying</span>
            </div>
        ),
        cell: (info) => info.row.original.forex.buying,
    },
    {
        id: "forex_selling",
        accessorKey: "forex_selling",
        localizedHeader: "Döviz Satış",
        header: () => (
            <div className="flex flex-col">
                <span className="!leading-3 text-2xs font-bold text-gray-900">
                    Döviz Satış
                </span>
                <span className="!leading-3 !text-xs">Forex Selling</span>
            </div>
        ),
        cell: (info) => info.row.original.forex.selling,
    },
    {
        id: "banknote_buying",
        accessorKey: "banknote_buying",
        localizedHeader: "Banknot Alış",
        header: () => (
            <div className="flex flex-col">
                <span className="!leading-3 text-2xs font-bold text-gray-900">
                    Efektif Alış
                </span>
                <span className="!leading-3 !text-xs/[0.95rem] ">
                    Banknote Buying
                </span>
            </div>
        ),
        cell: (info) => info.row.original.banknote.buying,
    },
    {
        id: "banknote_selling",
        accessorKey: "banknote_selling",
        localizedHeader: "Banknot Satış",
        header: () => (
            <div className="flex flex-col">
                <span className="!leading-3 text-2xs font-bold text-gray-900">
                    Efektif Satış
                </span>
                <span className="!leading-3 !text-xs">Banknote Selling</span>
            </div>
        ),
        cell: (info) => info.row.original.banknote.selling,
    },
    {
        id: "date",
        accessorKey: "date",
        localizedHeader: "Tarih",
        header: () => (
            <div className="flex flex-col">
                <span className="!leading-3 text-2xs font-bold text-gray-900">
                    Tarih
                </span>
                <span className="!leading-3 !text-xs">Date</span>
            </div>
        ),
        cell: (info) => info.getValue(),
    },
];

export default columns;
