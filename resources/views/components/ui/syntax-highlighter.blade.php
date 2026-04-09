@props([
    'code' => '',
    'lang' => 'php',
    'showLineNumbers' => true,
    'maxHeight' => 'max-h-[500px]'
])

@php
    $highlight = function($text, $lang) {
        $text = htmlspecialchars($text);
        
        // Strings
        $text = preg_replace("/(&quot;.*?&quot;|&#039;.*?&#039;)/", '<span class="text-[#e0af68] italic">$1</span>', $text);
        
        // Comments
        $text = preg_replace("/(\/\/.*)/", '<span class="text-[#565f89] italic opacity-70">$1</span>', $text);
        $text = preg_replace("/(\/\*.*?\*\/)/s", '<span class="text-[#565f89] italic opacity-70">$1</span>', $text);

        // Keywords
        $keywords = [
            'php' => ['public', 'private', 'protected', 'function', 'class', 'const', 'return', 'if', 'else', 'elseif', 'foreach', 'as', 'use', 'namespace', 'new', 'static', 'extends', 'implements', 'try', 'catch', 'finally', 'throw', 'echo', 'print', 'die', 'exit'],
            'javascript' => ['export', 'import', 'const', 'let', 'var', 'function', 'async', 'await', 'return', 'if', 'else', 'for', 'while', 'switch', 'case', 'break', 'new', 'this', 'extends', 'class', 'from'],
            'css' => ['@media', '@import', '@extend', '@mixin', '@include', 'important', 'hover', 'focus', 'active'],
            'default' => ['if', 'else', 'return', 'class', 'new', 'var', 'let', 'const']
        ];
        
        $currentKeywords = $keywords[strtolower($lang)] ?? $keywords['default'];
        foreach($currentKeywords as $word) {
            $text = preg_replace("/\b($word)\b/", '<span class="text-[#bb9af7] font-bold">$1</span>', $text);
        }

        // Logic & Control
        $text = preg_replace("/\b(true|false|null)\b/i", '<span class="text-[#ff9e64] font-black">$1</span>', $text);
        
        // Numbers
        $text = preg_replace("/\b(\d+)\b/", '<span class="text-[#ff9e64]">$1</span>', $text);

        // Variables (PHP)
        if (strtolower($lang) === 'php') {
            $text = preg_replace("/(\\$[a-zA-Z_]\w*)/", '<span class="text-[#f7768e]">$1</span>', $text);
        }

        // Functions
        $text = preg_replace("/(\w+)\s*\(/", '<span class="text-[#7dcfff] font-bold">$1</span>(', $text);

        // HTML Tags
        if (in_array(strtolower($lang), ['html', 'blade', 'xml'])) {
            $text = preg_replace("/(&lt;\/?[a-z0-9]+.*?&gt;)/i", '<span class="text-[#f7768e]">$1</span>', $text);
        }

        return $text;
    };

    $highlightedLines = explode("\n", $highlight($code, $lang));
@endphp

<div class="bg-[#0d0e14] rounded-2xl border border-white/5 font-mono text-[11px] leading-relaxed overflow-hidden shadow-2xl {{ $maxHeight }} flex flex-col">
    <div class="overflow-auto custom-scrollbar flex-1">
        <div class="p-6 min-w-full inline-block">
            @foreach($highlightedLines as $index => $line)
                <div class="flex gap-6 group/highlight-line hover:bg-white/[0.03] transition-colors -mx-6 px-6">
                    @if($showLineNumbers)
                        <span class="w-8 shrink-0 text-right text-on-surface-variant/20 select-none group-hover/highlight-line:text-primary transition-colors font-bold">{{ $index + 1 }}</span>
                    @endif
                    <pre class="flex-1 whitespace-pre m-0 text-on-surface/90 font-medium tracking-wide group-hover/highlight-line:text-white transition-colors">{!! $line ?: '&nbsp;' !!}</pre>
                </div>
            @endforeach
        </div>
    </div>
</div>
