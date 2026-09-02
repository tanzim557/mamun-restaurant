@extends('layouts.app')

@section('title', 'গোপনীয়তা নীতি (Privacy Policy) — শ্যামনগর নজরুল হোটেল')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto text-slate-800 dark:text-slate-100">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-12 shadow-xl border border-slate-200 dark:border-slate-800">
        <!-- Header -->
        <div class="text-center pb-8 border-b border-slate-200 dark:border-slate-800">
            <span class="inline-block px-4 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 font-semibold text-xs tracking-wider uppercase mb-3">
                আইনি সুরক্ষা ও ডেটা নিরাপত্তা
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">
                গোপনীয়তা নীতি (Privacy Policy)
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 text-sm">
                সর্বশেষ হালনাগাদ: সেপ্টেম্বর ২০২৬ • শ্যামনগর নজরুল হোটেল (খাবার অর্ডার ও ম্যানেজমেন্ট অ্যাপ)
            </p>
        </div>

        <!-- Content -->
        <div class="mt-8 space-y-6 text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-300">
            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">১. ভূমিকা (Introduction)</h2>
                <p>
                    শ্যামনগর নজরুল হোটেল ("আমরা", "আমাদের" বা "হোটেল কর্তৃপক্ষ") গ্রাহক এবং ব্যবহারকারীদের ব্যক্তিগত তথ্যের গোপনীয়তা ও সুরক্ষাকে সর্বোচ্চ অগ্রাধিকার প্রদান করে। এই গোপনীয়তা নীতি আমাদের মোবাইল অ্যাপ্লিকেশন (Android & iOS) এবং ওয়েবসাইটের সকল ব্যবহারকারীর জন্য প্রযোজ্য।
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">২. সংগৃহীত তথ্যাবলী (Information We Collect)</h2>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>অর্ডার ও যোগাযোগ তথ্য:</strong> গ্রাহকের নাম, মোবাইল নম্বর এবং খাবার পৌঁছে দেওয়ার ঠিকানা।</li>
                    <li><strong>অর্ডার হিস্ট্রি:</strong> খাবারের তালিকা, পেমেন্ট মাধ্যম (ক্যাশ অন ডেলিভারি / অনলাইন) এবং ট্র্যাকিং স্ট্যাটাস।</li>
                    <li><strong>ডিভাইস তথ্য:</strong> অ্যাপের স্থিতিশীলতা এবং সাইবার সিকিউরিটির জন্য ডিভাইসের মডেল, ওএস ভার্সন ও ইন্টারনেট নেটওয়ার্ক স্ট্যাটাস।</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">৩. ক্যামেরা ও স্টোরেজ অনুমতি (Permissions Used)</h2>
                <p>
                    আমাদের অ্যাপে শুধুমাত্র অ্যাডমিন বা অনুমোদিত ব্যবহারকারী কর্তৃক খাবারের ছবি মেনু ও স্টকে যুক্ত করার সুবিধার্থে <code>CAMERA</code> এবং <code>READ_MEDIA_IMAGES</code> অনুমতি চাওয়া হয়। কোনো অপ্রয়োজনীয় ব্যাকগ্রাউন্ড ডেটা বা ব্যক্তিগত গ্যালারির ছবি সংগ্রহ করা হয় না।
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">৪. তথ্যের ব্যবহার ও নিরাপত্তা (Data Usage & Security)</h2>
                <p>
                    গ্রাহকের তথ্য শুধুমাত্র খাবার রান্না, ডেলিভারি সম্পন্নকরণ এবং অর্ডার ট্র্যাকিং সেবা পরিচালনার উদ্দেশ্যে ব্যবহৃত হয়। সমস্ত ডেটা ২৫৬-বিট এসএসএল (SSL/TLS 1.3) ক্রিপ্টোগ্রাফিক এনক্রিপশনের মাধ্যমে স্থানান্তরিত ও সুরক্ষিত ক্লাউড ডেটাবেসে সংরক্ষিত হয়।
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">৫. তৃতীয় পক্ষের সাথে তথ্য ভাগাভাগি (Third-Party Sharing)</h2>
                <p>
                    আমরা কোনো গ্রাহকের ব্যক্তিগত ডেটা, ফোন নম্বর বা তথ্য কোনো তৃতীয় পক্ষ, বিজ্ঞাপনী সংস্থা বা মার্কেটিং এজেন্সির কাছে বিক্রি, ভাড়া বা হস্তান্তর করি না।
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">৬. ডেটা মুছে ফেলার অধিকার (Data Deletion Rights)</h2>
                <p>
                    গ্রাহক চাইলে যেকোনো সময় তাদের অ্যাকাউন্ট বা সংরক্ষিত অর্ডারের ডেটা মুছে ফেলার (Right to be Forgotten) জন্য সরাসরি হোটেল কর্তৃপক্ষের সাথে যোগাযোগ করে অনুরোধ জানাতে পারেন।
                </p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-2">৭. যোগাযোগ (Contact Us)</h2>
                <p>
                    গোপনীয়তা নীতি সম্পর্কিত যেকোনো প্রশ্ন বা তথ্যের জন্য যোগাযোগ করুন:<br>
                    <strong>শ্যামনগর নজরুল হোটেল</strong><br>
                    ঠিকানা: শ্যামনগর বাজার, সাতক্ষীরা<br>
                    হটলাইন: ০১৭৯৪-৯৩২০৯৭ / ০১৭৩৭-৫২৫৯৯৭
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
