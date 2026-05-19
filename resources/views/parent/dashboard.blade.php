@extends('layouts.dashboard')

@section('title', 'Tableau de Bord Parent')

@section('content')
<div class="space-y-6" data-aos="fade-up">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- HEADER                                                      --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="relative bg-white dark:bg-gray-800 border border-gray-200 dark:border-slate-700/60 rounded-2xl p-5 shadow-md overflow-hidden">
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-indigo-500/10 dark:bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-purple-500/10 dark:bg-purple-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="relative flex-shrink-0">
                <span class="absolute inset-0 rounded-full bg-indigo-400 opacity-20 animate-ping"></span>
                <div class="relative w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/25">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/50 mb-1">
                    Portail Famille · UPF
                </span>
                <h1 class="text-lg font-extrabold text-gray-900 dark:text-white tracking-tight">
                    Bienvenue sur votre espace parent
                </h1>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">
                    Suivez la scolarité de votre enfant grâce à l'Assistant E-UPF.
                </p>
            </div>

            <div class="flex gap-3 flex-shrink-0">
                <div class="px-4 py-2.5 bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700 rounded-xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Statut</p>
                    <span class="inline-flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-ping"></span>
                        Actif & Lié
                    </span>
                </div>
                <div class="px-4 py-2.5 bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-700 rounded-xl text-center">
                    <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Étudiant</p>
                    <span class="text-xs font-bold text-gray-700 dark:text-slate-300 mt-0.5 block">
                        {{ $studentName ?? auth()->user()->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- STATS + GRAPHIQUE (null guard)                             --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @if($student)

        {{-- The chatbot is displayed below --}}

    @else
        {{-- ── Aucun étudiant lié ───────────────────────────────────── --}}
        <div class="bg-white dark:bg-gray-800 border border-amber-200 dark:border-amber-700/40 rounded-2xl p-8 shadow-sm text-center">
            <div class="w-14 h-14 bg-amber-50 dark:bg-amber-950/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-base font-extrabold text-gray-800 dark:text-white mb-1">Aucun étudiant associé</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 max-w-sm mx-auto">
                Votre compte parent n'est pas encore lié à un profil étudiant. Contactez l'administration de l'UPF.
            </p>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- CHATBOT (toujours visible)                                 --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div
        x-data="{
            messages: [],
            input: '',
            loading: false,
            limit: false,
            welcomeLoading: true,

            async init() {
                // 1. Try history first
                try {
                    const histRes = await fetch('{{ route('parent.chatbot.history') }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                    });
                    const hist = await histRes.json();
                    if (hist.length > 0) {
                        this.messages = hist.map(m => ({
                            id: m.id,
                            role: m.role,
                            content: m.content,
                            feedback: m.feedback,
                            isWelcome: m.is_welcome,
                        }));
                        this.welcomeLoading = false;
                        this.$nextTick(() => {
                            const el = this.$refs.messageArea;
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                        return;
                    }
                } catch (e) {}

                // 2. No history — fetch dynamic welcome
                try {
                    const res = await fetch('{{ route('parent.chatbot.welcome') }}', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        },
                    });
                    const data = await res.json();
                    this.messages.push({ id: data.id ?? null, role: 'assistant', content: data.message, feedback: null, isWelcome: true });
                } catch (e) {
                    this.messages.push({ id: null, role: 'assistant', content: 'Bonjour ! Je suis votre Assistant E-UPF. Comment puis-je vous aider concernant la scolarité de votre enfant ?', feedback: null, isWelcome: true });
                } finally {
                    this.welcomeLoading = false;
                    this.$nextTick(() => {
                        const el = this.$refs.messageArea;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                }
            },

            async saveFeedback(msgId, fb) {
                if (!msgId) return;
                try {
                    await fetch('{{ route('parent.chatbot.feedback') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        },
                        body: JSON.stringify({ message_id: msgId, feedback: fb }),
                    });
                } catch (e) {}
            },

            async sendMessage() {
                if (!this.input.trim() || this.loading || this.limit) return;

                const text = this.input.trim();
                this.messages.push({ id: null, role: 'user', content: text });
                this.input = '';
                this.loading = true;

                const history = this.messages
                    .slice(0, -1)
                    .slice(-6)
                    .map(m => ({ role: m.role, content: m.content }));

                this.$nextTick(() => {
                    const el = this.$refs.messageArea;
                    if (el) el.scrollTop = el.scrollHeight;
                });

                try {
                    const res = await fetch('{{ route('parent.chatbot') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        },
                        body: JSON.stringify({ message: text, history }),
                    });

                    const data = await res.json();

                    if (res.status === 429) {
                        this.limit = true;
                        this.messages.push({ id: null, role: 'assistant', content: data.reply, feedback: null, isWelcome: false });
                    } else {
                        this.messages.push({ id: data.message_id ?? null, role: 'assistant', content: data.reply, feedback: null, isWelcome: false });
                    }
                } catch (e) {
                    this.messages.push({ id: null, role: 'assistant', content: 'L\'assistant est indisponible. Réessayez plus tard.', feedback: null, isWelcome: false });
                } finally {
                    this.loading = false;
                    this.$nextTick(() => {
                        const el = this.$refs.messageArea;
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                }
            },

            quickQuestion(text) {
                this.input = text;
                this.sendMessage();
            }
        }"
        class="flex flex-col bg-white dark:bg-gray-900 border border-gray-200 dark:border-slate-700/50 rounded-2xl shadow-lg overflow-hidden"
    >
        {{-- Chat header --}}
        <div class="relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 overflow-hidden">
            <div class="absolute -top-6 -right-6 w-32 h-32 bg-purple-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-indigo-400/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-xl flex items-center justify-center text-white shadow-md shadow-indigo-900/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-indigo-900"></span>
            </div>

            <div class="flex-1 min-w-0">
                <h2 class="font-extrabold text-white text-sm tracking-tight leading-none">Assistant E-UPF</h2>
                <p class="text-indigo-200 text-xs font-medium mt-0.5">Assistant Scolaire · UPF</p>
            </div>

            <div class="flex-shrink-0 flex items-center gap-2">
                <span x-show="!limit" class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/10 text-indigo-100 rounded-full text-[10px] font-bold border border-white/10 backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    En ligne
                </span>
                <span x-show="limit" x-cloak class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-400/20 text-amber-200 rounded-full text-[10px] font-bold border border-amber-400/20">
                    <i class="fa-solid fa-clock-rotate-left text-[9px]"></i>
                    Limite atteinte
                </span>
            </div>
        </div>

        {{-- Suggestions rapides (contextuelles) --}}
        <div class="px-5 pt-4 pb-2 flex flex-wrap gap-2 border-b border-gray-100 dark:border-slate-800">
            @foreach($suggestionsChat as $suggestion)
                <button
                    x-on:click="quickQuestion('{{ $suggestion }}')"
                    :disabled="loading || limit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border transition-all duration-150 disabled:opacity-40 disabled:cursor-not-allowed
                           {{ $loop->index % 2 === 0
                               ? 'border-indigo-200 dark:border-indigo-700/60 text-indigo-600 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 hover:border-indigo-400 dark:hover:border-indigo-500'
                               : 'border-purple-200 dark:border-purple-700/60 text-purple-600 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/30 hover:bg-purple-100 dark:hover:bg-purple-900/40 hover:border-purple-400 dark:hover:border-purple-500' }}"
                >
                    <i class="fa-solid {{ $loop->index % 2 === 0 ? 'fa-graduation-cap' : 'fa-calendar-xmark' }} text-[10px]"></i>
                    {{ $suggestion }}
                </button>
            @endforeach
        </div>

        {{-- Zone de messages --}}
        <div
            x-ref="messageArea"
            class="overflow-y-auto px-5 py-5 space-y-5 scroll-smooth bg-gray-50 dark:bg-gray-950/40"
            style="height: 450px;"
        >
            {{-- Chargement du message de bienvenue --}}
            <div x-show="welcomeLoading" x-cloak class="flex justify-start">
                <div class="flex items-end gap-2.5">
                    <div class="flex-shrink-0 w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/60 border-l-4 border-l-indigo-500 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <div class="flex gap-1">
                                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                <span class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-slate-500 font-medium italic">L'Assistant E-UPF prépare votre résumé...</span>
                        </div>
                    </div>
                </div>
            </div>

            <template x-for="(msg, idx) in messages" :key="idx">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <template x-if="msg.role === 'assistant'">
                        <div class="flex items-start gap-2.5 max-w-[85%] sm:max-w-[72%]">
                            <div class="flex-shrink-0 w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm shadow-indigo-500/30 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/60 border-l-4 border-l-indigo-500 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                                    <p class="text-sm text-gray-800 dark:text-slate-200 whitespace-pre-line leading-relaxed" x-text="msg.content"></p>
                                </div>
                                <template x-if="!msg.isWelcome">
                                    <div class="flex items-center gap-1.5 mt-1.5 ml-1">
                                        <div x-show="msg.feedback === null" class="flex gap-1">
                                            <button
                                                x-on:click="msg.feedback = 'up'; saveFeedback(msg.id, 'up')"
                                                class="p-1 rounded-md text-gray-400 dark:text-slate-600 hover:text-green-500 hover:bg-green-50 dark:hover:bg-green-950/30 transition-all duration-150 text-sm leading-none"
                                                title="Réponse utile"
                                            >👍</button>
                                            <button
                                                x-on:click="msg.feedback = 'down'; saveFeedback(msg.id, 'down')"
                                                class="p-1 rounded-md text-gray-400 dark:text-slate-600 hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all duration-150 text-sm leading-none"
                                                title="Réponse non utile"
                                            >👎</button>
                                        </div>
                                        <span
                                            x-show="msg.feedback !== null"
                                            x-cloak
                                            x-text="msg.feedback === 'up' ? '👍 Merci pour votre retour !' : '👎 Merci, nous en tenons compte.'"
                                            class="text-[11px] text-gray-400 dark:text-slate-500 font-medium"
                                        ></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template x-if="msg.role === 'user'">
                        <div class="max-w-[85%] sm:max-w-[72%]">
                            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl rounded-br-sm px-4 py-3 shadow-md shadow-indigo-600/25">
                                <p class="text-sm text-white whitespace-pre-line leading-relaxed" x-text="msg.content"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Chargement --}}
            <div x-show="loading" x-cloak class="flex justify-start">
                <div class="flex items-end gap-2.5">
                    <div class="flex-shrink-0 w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                        <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>
                    <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/60 border-l-4 border-l-indigo-500 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <div class="flex gap-1">
                                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                                <span class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                            </div>
                            <span class="text-xs text-gray-400 dark:text-slate-500 font-medium italic">L'assistant analyse le dossier...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bannière limite --}}
        <div x-show="limit" x-cloak class="px-5 pt-3">
            <div class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 rounded-xl text-amber-700 dark:text-amber-400 text-xs font-semibold">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Vous avez atteint la limite de 20 questions par jour. Revenez demain.
            </div>
        </div>

        {{-- Zone de saisie --}}
        <div class="px-5 py-4 border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-gray-900">
            <form x-on:submit.prevent="sendMessage()" class="flex items-end gap-3">
                <div class="flex-1 relative">
                    <textarea
                        x-model="input"
                        x-on:keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                        :disabled="loading || limit"
                        placeholder="Posez votre question sur la scolarité de votre enfant..."
                        rows="1"
                        class="w-full resize-none rounded-xl border text-sm font-medium transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed
                               bg-gray-50 dark:bg-slate-800/80
                               border-gray-200 dark:border-slate-700
                               text-gray-900 dark:text-slate-100
                               placeholder-gray-400 dark:placeholder-slate-500
                               px-4 py-3
                               focus:outline-none focus:ring-2 focus:ring-indigo-400/40 focus:border-indigo-500 dark:focus:border-indigo-500"
                        style="max-height: 120px; overflow-y: auto;"
                        x-on:input="$el.style.height = 'auto'; $el.style.height = Math.min($el.scrollHeight, 120) + 'px'"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    :disabled="!input.trim() || loading || limit"
                    class="flex-shrink-0 w-11 h-11 rounded-xl flex items-center justify-center text-white transition-all duration-150 shadow-md disabled:opacity-40 disabled:cursor-not-allowed disabled:shadow-none
                           bg-gradient-to-br from-indigo-500 to-purple-600
                           hover:from-indigo-400 hover:to-purple-500
                           shadow-indigo-500/30 hover:shadow-indigo-500/50"
                >
                    <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                    <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </form>
            <p class="mt-2 text-center text-[10px] text-gray-400 dark:text-slate-600 font-medium">
                Entrée pour envoyer · Shift+Entrée pour nouvelle ligne · 20 questions/jour
            </p>
        </div>

    </div>{{-- /chatbot --}}

</div>
@endsection

