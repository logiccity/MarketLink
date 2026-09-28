@extends('layouts.customer')

@section('title', 'AI Market Assistant')
@section('page-title', 'AI Market Assistant')

@section('content')

<div class="row g-4">

  {{-- Chat Panel --}}
  <div class="col-lg-8">
    <div class="portal-card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; min-height: 72vh;">

      {{-- Chat Header --}}
      <div style="background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); padding: 1.5rem 1.75rem; display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">🌿</div>
        <div>
          <div style="font-family: var(--font-serif); font-weight: 700; font-size: 1.15rem; color: #FFF;">eGreen Basket Assistant</div>
          <div style="font-size: 0.78rem; color: rgba(255,255,255,0.75);">Powered by MarketLink AI · Always available</div>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
          <span style="width: 8px; height: 8px; background: #4ade80; border-radius: 50%; display: inline-block; box-shadow: 0 0 6px rgba(74,222,128,0.8);"></span>
          <span style="font-size: 0.75rem; color: rgba(255,255,255,0.8);">Online</span>
        </div>
      </div>

      {{-- Chat Messages --}}
      <div id="page-ai-chat-messages" style="flex: 1; overflow-y: auto; padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; background: var(--color-bg); min-height: 400px;">
        <div class="d-flex gap-3 align-items-start">
          <div style="width: 36px; height: 36px; background: var(--color-sage-soft); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; border: 1.5px solid rgba(168,201,160,0.4);">🌿</div>
          <div style="background: #FFF; border: 1px solid var(--color-border); border-radius: 0 16px 16px 16px; padding: 1rem 1.25rem; max-width: 85%; box-shadow: 0 1px 4px rgba(0,0,0,0.05);">
            <div style="font-size: 0.88rem; color: var(--color-text-dark); line-height: 1.6;">
              Hello, <strong>{{ explode(' ', auth()->user()->name)[0] }}</strong>! 👋 I'm your <strong>eGreen Basket AI Assistant</strong>.<br><br>
              I can help you:
              <ul class="mt-2 mb-1" style="padding-left: 1.2rem; font-size: 0.85rem;">
                <li>🥦 Find fresh produce and check availability</li>
                <li>🗓️ Discover markets open on specific days</li>
                <li>👨‍🌾 Browse local farmer profiles</li>
                <li>📦 Understand how pre-orders &amp; pickup work</li>
              </ul>
              What can I help you find today?
            </div>
          </div>
        </div>
      </div>

      {{-- Input Area --}}
      <div style="padding: 1rem 1.25rem; border-top: 1px solid var(--color-border); background: #FFF;">
        <form id="page-ai-form" class="d-flex gap-2">
          <input
            type="text"
            id="page-ai-prompt"
            class="form-control rounded-pill"
            style="border-color: var(--color-border); font-size: 0.88rem; padding: 0.65rem 1.1rem;"
            placeholder="Ask about fresh produce, markets, farmers, orders…"
            required
            maxlength="400"
            autocomplete="off"
          >
          <button type="submit" id="page-ai-send-btn" class="btn rounded-pill px-4 d-flex align-items-center gap-2" style="background: var(--color-primary); color: #FFF; font-weight: 600; font-size: 0.88rem; border: none; white-space: nowrap;">
            <span id="page-ai-btn-text"><i class="bi bi-send-fill"></i></span>
            <span id="page-ai-spinner" class="d-none spinner-border spinner-border-sm" role="status"></span>
          </button>
        </form>
        <div style="font-size: 0.72rem; color: var(--color-text-muted); margin-top: 0.5rem; text-align: center;">
          AI responses are based on real-time data from MarketLink. Always confirm details with the farmer directly.
        </div>
      </div>
    </div>
  </div>

  {{-- Quick Prompts Sidebar --}}
  <div class="col-lg-4">
    <div class="portal-card mb-4">
      <div class="portal-card-header">
        <h3 class="portal-card-title"><i class="bi bi-lightning-charge-fill me-2" style="color: var(--color-accent);"></i>Quick Questions</h3>
      </div>
      <div class="portal-card-body" style="padding: 0.5rem 1.25rem 1.25rem;">
        @php
          $quickPrompts = [
            ['icon' => '🥦', 'text' => 'What fresh vegetables are available this week?'],
            ['icon' => '🍎', 'text' => 'Show me fresh fruits available'],
            ['icon' => '🗓️', 'text' => 'Which markets are open on Saturday?'],
            ['icon' => '👨‍🌾', 'text' => 'Tell me about local farmers'],
            ['icon' => '🥛', 'text' => 'Find dairy and eggs near me'],
            ['icon' => '📦', 'text' => 'How does the pre-order process work?'],
            ['icon' => '💰', 'text' => 'How do I pay for my order?'],
            ['icon' => '⏰', 'text' => 'What are the pickup time slots?'],
            ['icon' => '🌿', 'text' => 'Show me organic produce options'],
          ];
        @endphp
        <div class="d-flex flex-column gap-2">
          @foreach($quickPrompts as $prompt)
            <button
              type="button"
              class="btn text-start rounded-3 page-quick-prompt-btn"
              style="background: var(--color-bg); border: 1px solid var(--color-border); color: var(--color-text-dark); font-size: 0.82rem; padding: 0.6rem 0.9rem; transition: all 0.2s;"
              data-prompt="{{ $prompt['text'] }}"
              onmouseover="this.style.background='var(--color-sage-soft)'; this.style.borderColor='rgba(168,201,160,0.6)';"
              onmouseout="this.style.background='var(--color-bg)'; this.style.borderColor='var(--color-border)';"
            >
              <span style="margin-right: 0.5rem;">{{ $prompt['icon'] }}</span>{{ $prompt['text'] }}
            </button>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Tips --}}
    <div class="portal-card">
      <div class="portal-card-header">
        <h3 class="portal-card-title"><i class="bi bi-lightbulb-fill me-2" style="color: var(--color-accent);"></i>Tips</h3>
      </div>
      <div class="portal-card-body" style="padding: 0.75rem 1.25rem 1.25rem;">
        <div class="d-flex flex-column gap-3" style="font-size: 0.82rem; color: var(--color-text-muted);">
          <div class="d-flex gap-2">
            <span style="flex-shrink: 0;">💡</span>
            <span>Ask by <strong>day</strong>: <em>"Markets open on Sunday?"</em></span>
          </div>
          <div class="d-flex gap-2">
            <span style="flex-shrink: 0;">💡</span>
            <span>Ask by <strong>product</strong>: <em>"Where can I find honey?"</em></span>
          </div>
          <div class="d-flex gap-2">
            <span style="flex-shrink: 0;">💡</span>
            <span>Ask about <strong>orders</strong>: <em>"How does pickup work?"</em></span>
          </div>
          <div class="d-flex gap-2">
            <span style="flex-shrink: 0;">💡</span>
            <span>Responses include <strong>direct links</strong> to products and markets.</span>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form     = document.getElementById('page-ai-form');
  const input    = document.getElementById('page-ai-prompt');
  const messages = document.getElementById('page-ai-chat-messages');
  const spinner  = document.getElementById('page-ai-spinner');
  const btnText  = document.getElementById('page-ai-btn-text');
  const sendBtn  = document.getElementById('page-ai-send-btn');
  const userInitial = '{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}';

  function appendMessage(html, isUser = false) {
    const wrapper = document.createElement('div');
    wrapper.className = 'd-flex gap-3 align-items-start' + (isUser ? ' flex-row-reverse' : '');

    const avatar = document.createElement('div');
    avatar.style.cssText = 'width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;';
    if (isUser) {
      avatar.style.background = 'linear-gradient(135deg, var(--color-secondary), var(--color-primary))';
      avatar.style.color = '#FFF';
      avatar.style.fontWeight = '700';
      avatar.textContent = userInitial;
    } else {
      avatar.style.background = 'var(--color-sage-soft)';
      avatar.style.border = '1.5px solid rgba(168,201,160,0.4)';
      avatar.textContent = '🌿';
    }

    const bubble = document.createElement('div');
    bubble.style.cssText = isUser
      ? 'background:var(--color-primary);color:#FFF;border-radius:16px 0 16px 16px;padding:0.85rem 1.1rem;max-width:85%;font-size:0.86rem;line-height:1.6;'
      : 'background:#FFF;border:1px solid var(--color-border);border-radius:0 16px 16px 16px;padding:0.85rem 1.1rem;max-width:85%;box-shadow:0 1px 4px rgba(0,0,0,0.05);font-size:0.86rem;line-height:1.6;color:var(--color-text-dark);';
    bubble.innerHTML = isUser ? html.replace(/</g, '&lt;').replace(/>/g, '&gt;') : html;

    wrapper.appendChild(avatar);
    wrapper.appendChild(bubble);
    messages.appendChild(wrapper);
    messages.scrollTop = messages.scrollHeight;
  }

  function showTyping() {
    const id = 'typing-' + Date.now();
    const div = document.createElement('div');
    div.id = id;
    div.className = 'd-flex gap-3 align-items-start';
    div.innerHTML = `
      <div style="width:36px;height:36px;background:var(--color-sage-soft);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;border:1.5px solid rgba(168,201,160,0.4);">🌿</div>
      <div style="background:#FFF;border:1px solid var(--color-border);border-radius:0 16px 16px 16px;padding:0.85rem 1.1rem;box-shadow:0 1px 4px rgba(0,0,0,0.05);">
        <div class="d-flex gap-1 align-items-center" style="height:18px;">
          <div style="width:7px;height:7px;background:var(--color-text-muted);border-radius:50%;animation:dot-bounce 1s infinite 0s;"></div>
          <div style="width:7px;height:7px;background:var(--color-text-muted);border-radius:50%;animation:dot-bounce 1s infinite 0.2s;"></div>
          <div style="width:7px;height:7px;background:var(--color-text-muted);border-radius:50%;animation:dot-bounce 1s infinite 0.4s;"></div>
        </div>
      </div>`;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
    return id;
  }

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const prompt = input.value.trim();
    if (!prompt) return;

    appendMessage(prompt, true);
    input.value = '';
    sendBtn.disabled = true;
    spinner.classList.remove('d-none');
    btnText.classList.add('d-none');
    input.disabled = true;

    const typingId = showTyping();

    try {
      const res = await fetch('{{ route("ai.assistant.ask") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ prompt })
      });
      const data = await res.json();
      document.getElementById(typingId)?.remove();
      appendMessage(data.reply || data.response || 'Sorry, I could not understand that.');
    } catch (err) {
      document.getElementById(typingId)?.remove();
      appendMessage('⚠️ Connection issue. Please try again in a moment.');
    } finally {
      sendBtn.disabled = false;
      spinner.classList.add('d-none');
      btnText.classList.remove('d-none');
      input.disabled = false;
      input.focus();
    }
  });

  document.querySelectorAll('.page-quick-prompt-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      input.value = this.dataset.prompt;
      form.dispatchEvent(new Event('submit'));
    });
  });
});
</script>
<style>
@keyframes dot-bounce {
  0%, 80%, 100% { transform: scale(0.65); opacity: 0.4; }
  40% { transform: scale(1.1); opacity: 1; }
}
</style>
@endpush
