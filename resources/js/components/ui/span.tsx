import * as React from 'react';
import { cn } from '@/lib/utils';

type SpanProps = React.HTMLAttributes<HTMLSpanElement>;

function Span({ className, ...props }: SpanProps) {
    return (
        <span
            data-slot="span"
            className={cn(
                'inline-block align-middle',
                className
            )}
            {...props}
        />
    );
}

export { Span };
