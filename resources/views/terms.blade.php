@extends('layouts.app')

@section('title', 'ব্যবহারের শর্তাবলী (Terms & Conditions) — শ্যামনগর নজরুল হোটেল')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto text-slate-800 dark:text-slate-100">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-12 shadow-xl border border-slate-200 dark:border-slate-800">
        <!-- Header -->
        <div class="text-center pb-8 border-b border-slate-200 dark:border-slate-800">
            <span class="inline-block px-4 py-1 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 font-semibold text-xs tracking-wider uppercase mb-3">
                সেবা নীতিমালা ও শর্তাবলী
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">
                ব্যবহারের শর্তাবলী (Terms & Conditions)
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 text-sm">
                শ্যামনগর নজরুল হোটেল • অনলাইন ও অ্যাপ সার্ভিস নীতিমালা
            </p>
        </div>

        <!-- Content -->
        <div class="mt-8 space-y-6 text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-300">
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">১. সেবা গ্রহণযোগ্যতা</h2>
                <p>
                    শ্যামনগর নজরুল হোটেলের ওয়েবসাইট বা মোবাইল অ্যাপ ব্যবহারের মাধ্যমে গ্রাহক এই শর্তাবলীর সাথে পূর্ণ সম্মতি জ্ঞাপন করছেন।
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">২. খাবার অর্ডার ও কনফার্মেশন</h2>
                <p>
                    গ্রাহক অ্যাপ বা ওয়েবসাইটের মাধ্যমে অর্ডার প্রদান করার পর কিচেন থেকে লাইভ স্ট্যাটাস (অপেক্ষমাণ → রান্নায় → ডেলিভারিতে) আপডেট করা হবে। কিচেনে রান্নার কাজ শুরু হওয়ার পর অর্ডার বাতিল বা পরিবর্তন গ্রহণযোগ্য নয়।
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">৩. মূল্য পরিশোধ ও ডেলিভারি</h2>
                <p>
                    অর্ডারের মূল্য ক্যাশ অন ডেলিভারি (নগদ টাকা) অথবা অনুমোদিত পেমেন্ট চ্যানেলে পরিশোধ করতে হবে। ডেলিভারি লোকেশন অনুযায়ী ডেলিভারি সময় সাময়িক ট্র্যাফিক বা আবহাওয়ার কারণে পরিবর্তিত হতে পারে।
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">৪. হোটেলের অধিকার সংরক্ষণ</h2>
                <p>
                    যেকোনো অপ্রত্যাশিত পরিস্থিতিতে, কাঁচামালের অপ্রতুলতায় বা কিচেন বন্ধ থাকলে হোটেল কর্তৃপক্ষ যেকোনো অর্ডার বাতিল বা সময় পুনর্বিন্যাস করার পূর্ণ অধিকার সংরক্ষণ করে।
                </p>
            </section>
        </div>

        <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
            <a href="/" class="inline-flex items-center px-6 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition-all shadow-md hover:shadow-lg">
                ← মূল পাতায় ফিরে যান
            </a>
        </div>
    </div>
</div>
@endsection
