<?php
$file = 'app/views/templates/admin_header.php';
$content = file_get_contents($file);

// Replace sidebar background
$content = str_replace('bg-white border-r border-slate-200', 'bg-emerald-900 border-none', $content);

// Replace border bottom inside sidebar
$content = str_replace('border-b border-slate-100', 'border-b border-emerald-800', $content);

// Replace active state classes
$content = str_replace("bg-blue-50 text-blue-600", "bg-emerald-800 text-white", $content);

// Replace inactive state classes
$content = str_replace("text-slate-500 hover:bg-slate-50 hover:text-slate-900", "text-emerald-100/70 hover:bg-emerald-800 hover:text-white", $content);

// Replace section titles
$content = str_replace("text-slate-400 uppercase", "text-emerald-400/60 uppercase", $content);

// Replace logo box
$content = str_replace("bg-blue-600", "bg-emerald-500", $content);

// Replace close button mobile
$content = str_replace("text-slate-400 hover:text-slate-600", "text-emerald-400 hover:text-white", $content);

// Replace font colors for header in sidebar
$content = preg_replace('/<span x-show="sidebarOpen \|\| mobileOpen" class="font-bold text-slate-800/i', '<span x-show="sidebarOpen || mobileOpen" class="font-bold text-white', $content);

// Replace scrollbar
$content = str_replace('class="flex-1 overflow-y-auto py-4 px-3"', 'class="flex-1 overflow-y-auto py-4 px-3 scrollbar-hide"', $content);

// Fix stroke width for icons
$content = str_replace('stroke-width="2"', 'stroke-width="1.5"', $content);

file_put_contents($file, $content);
echo "Sidebar replaced.";
