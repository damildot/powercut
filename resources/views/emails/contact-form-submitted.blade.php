<x-mail::message>
# Yeni iletişim formu

**Ad Soyad:** {{ $contactMessage->full_name ?? $contactMessage->name }}

**E-posta:** {{ $contactMessage->email }}

@if($contactMessage->phone)
**Telefon:** {{ $contactMessage->phone }}
@endif

@if($contactMessage->subject)
**Konu:** {{ $contactMessage->subject }}
@endif

**Dil:** {{ strtoupper($contactMessage->locale ?? 'tr') }}

---

{{ $contactMessage->message }}

<x-mail::panel>
IP: {{ $contactMessage->ip ?? '—' }}
</x-mail::panel>
</x-mail::message>
