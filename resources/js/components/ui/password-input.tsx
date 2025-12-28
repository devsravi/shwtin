import * as React from 'react';
import { Eye, EyeOff } from 'lucide-react';

import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';

interface PasswordInputProps
    extends Omit<React.ComponentProps<typeof Input>, 'type'> {}

function PasswordInput({ className, ...props }: PasswordInputProps) {
    const [visible, setVisible] = React.useState(false);

    return (
        <div className="relative">
            {/* Input */}
            <Input
                {...props}
                type={visible ? 'text' : 'password'}
                className={cn(
                    'pr-14', // space for button
                    className
                )}
            />

            {/* Divider */}
            {/* <span
                aria-hidden
                className="
                    absolute right-12 top-1/2 -translate-y-1/2
                    h-5 w-px
                    bg-border
                "
            /> */}

            {/* Toggle Button */}
            <button
                type="button"
                tabIndex={-1}
                onClick={() => setVisible((v) => !v)}
                aria-label={visible ? 'Hide password' : 'Show password'}
                className="
                    border-1
                    absolute right-0 top-0
                    flex h-full w-12 items-center justify-center
                    rounded-r-lg
                    text-muted-foreground
                    hover:bg-muted
                    hover:text-foreground
                    focus:outline-none
                    cursor-pointer
                "
            >
                {visible ? (
                    <EyeOff className="h-5 w-5" />
                ) : (
                    <Eye className="h-5 w-5" />
                )}
            </button>
        </div>
    );
}

export { PasswordInput };
