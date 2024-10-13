import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export const tableParser = ({
    data,
    pageSize,
}: {
    data: any;
    pageSize: number;
}) => {
    const { data: rows, ...rest } = data;

    const elements = () => {
        let current = rest.meta.current_page;
        let last = rest.meta.last_page;
        let delta = 1;
        let left = current - delta;
        let right = current + delta + 1;
        let range = [];
        let rangeWithDots = [];
        let l;

        for (let i = 1; i <= last; i++) {
            if (i == 1 || i == last || (i >= left && i < right)) {
                range.push(i);
            }
        }

        for (let i of range) {
            if (l) {
                if (i - l === 2) {
                    rangeWithDots.push(l + 1);
                } else if (i - l !== 1) {
                    rangeWithDots.push("...");
                }
            }

            rangeWithDots.push(i);
            l = i;
        }

        console.log({
            range,
            rangeWithDots,
        });

        return rangeWithDots;
    };

    return {
        rows,
        elements: elements(),
        pageCount: Math.ceil(rows.length / pageSize),
        rowCount: rest.meta.total,
        ...rest,
    };
};
