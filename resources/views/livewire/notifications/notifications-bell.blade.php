<?php

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Volt\Component;

new class extends Component
{
    public function unreadCount()
    {
        return auth()->user()->unreadNotifications()->count();
    }

    public function notifications()
    {
        return auth()->user()->notifications()->take(10)->get();
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification && $notification->unread()) {
            $notification->markAsRead();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }
}; ?>

<div class="relative flex justify-center" x-data="{ open: false }">
    <button @click="open = !open" 
            class="relative flex items-center justify-center w-10 h-10 rounded-lg hover:bg-pink-50 transition-all text-neutral-700 hover:text-pink-600"
            :class="{ 'bg-pink-50 text-pink-600': open }"
            title="Notificaciones">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($this->unreadCount() > 0)
            <span class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-pink-600 rounded-full">
                {{ $this->unreadCount() > 9 ? '9+' : $this->unreadCount() }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute left-full ml-2 top-0 w-80 md:w-96 bg-white rounded-xl shadow-2xl border border-neutral-200 z-50 max-h-96 overflow-hidden flex flex-col">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-neutral-200 bg-gradient-to-r from-pink-50 to-purple-50">
            <h3 class="font-semibold text-neutral-900">{{ __('Notificaciones') }}</h3>
            @if($this->unreadCount() > 0)
                <button wire:click="markAllAsRead" 
                        class="text-sm text-pink-600 hover:text-pink-700 font-medium">
                    {{ __('Marcar todas como leídas') }}
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="overflow-y-auto flex-1">
            @php
                $notifications = $this->notifications();
            @endphp
            
            @if($notifications->count() > 0)
                <div class="divide-y divide-neutral-200">
                    @foreach($notifications as $notification)
                        <a href="{{ $notification->data['url'] ?? '#' }}" 
                           wire:navigate
                           wire:click="markAsRead('{{ $notification->id }}')"
                           class="block p-4 hover:bg-neutral-50 transition-colors {{ $notification->unread() ? 'bg-pink-50/50' : '' }}"
                           @click="open = false">
                            <div class="flex items-start gap-3">
                                <!-- Icon -->
                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                                    @if($notification->data['type'] === 'plan_created') bg-blue-100 text-blue-600
                                    @elseif($notification->data['type'] === 'plan_rated') bg-yellow-100 text-yellow-600
                                    @elseif($notification->data['type'] === 'badge_earned') bg-purple-100 text-purple-600
                                    @elseif($notification->data['type'] === 'photos_uploaded') bg-green-100 text-green-600
                                    @else bg-neutral-100 text-neutral-600
                                    @endif">
                                    @if($notification->data['type'] === 'plan_created')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @elseif($notification->data['type'] === 'plan_rated')
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @elseif($notification->data['type'] === 'badge_earned')
                                        <span class="text-xl">{{ $notification->data['badge_icon'] ?? '🏆' }}</span>
                                    @elseif($notification->data['type'] === 'photos_uploaded')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-neutral-900 {{ $notification->unread() ? 'font-semibold' : '' }}">
                                        {{ $notification->data['message'] ?? 'Nueva notificación' }}
                                    </p>
                                    <p class="text-xs text-neutral-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Unread indicator -->
                                @if($notification->unread())
                                    <div class="flex-shrink-0 w-2 h-2 bg-pink-600 rounded-full mt-2"></div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="text-4xl mb-2">🔔</div>
                    <p class="text-neutral-600 text-sm">{{ __('No hay notificaciones') }}</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        @if($notifications->count() > 0)
            <div class="p-3 border-t border-neutral-200 bg-neutral-50">
                <a href="{{ route('notifications.index') }}" 
                   wire:navigate
                   class="block text-center text-sm text-pink-600 hover:text-pink-700 font-medium">
                    {{ __('Ver todas las notificaciones') }}
                </a>
            </div>
        @endif
    </div>
</div>

