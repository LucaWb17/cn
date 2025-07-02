<html>
  <head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
      rel="stylesheet"
      as="style"
      onload="this.rel='stylesheet'"
      href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Space+Grotesk%3Awght%40400%3B500%3B700"
    />

    <title>Stitch Design</title>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64," />

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  </head>
  <body>
    <div class="relative flex size-full min-h-screen flex-col bg-[#232010] dark group/design-root overflow-x-hidden" style='font-family: "Space Grotesk", "Noto Sans", sans-serif;'>
      <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#4a4321] px-10 py-3">
          <div class="flex items-center gap-4 text-white">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M39.475 21.6262C40.358 21.4363 40.6863 21.5589 40.7581 21.5934C40.7876 21.655 40.8547 21.857 40.8082 22.3336C40.7408 23.0255 40.4502 24.0046 39.8572 25.2301C38.6799 27.6631 36.5085 30.6631 33.5858 33.5858C30.6631 36.5085 27.6632 38.6799 25.2301 39.8572C24.0046 40.4502 23.0255 40.7407 22.3336 40.8082C21.8571 40.8547 21.6551 40.7875 21.5934 40.7581C21.5589 40.6863 21.4363 40.358 21.6262 39.475C21.8562 38.4054 22.4689 36.9657 23.5038 35.2817C24.7575 33.2417 26.5497 30.9744 28.7621 28.762C30.9744 26.5497 33.2417 24.7574 35.2817 23.5037C36.9657 22.4689 38.4054 21.8562 39.475 21.6262ZM4.41189 29.2403L18.7597 43.5881C19.8813 44.7097 21.4027 44.9179 22.7217 44.7893C24.0585 44.659 25.5148 44.1631 26.9723 43.4579C29.9052 42.0387 33.2618 39.5667 36.4142 36.4142C39.5667 33.2618 42.0387 29.9052 43.4579 26.9723C44.1631 25.5148 44.659 24.0585 44.7893 22.7217C44.9179 21.4027 44.7097 19.8813 43.5881 18.7597L29.2403 4.41187C27.8527 3.02428 25.8765 3.02573 24.2861 3.36776C22.6081 3.72863 20.7334 4.58419 18.8396 5.74801C16.4978 7.18716 13.9881 9.18353 11.5858 11.5858C9.18354 13.988 7.18717 16.4978 5.74802 18.8396C4.58421 20.7334 3.72865 22.6081 3.36778 24.2861C3.02574 25.8765 3.02429 27.8527 4.41189 29.2403Z"
                  fill="currentColor"
                ></path>
              </svg>
            </div>
            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">CN</h2>
          </div>
          <div class="flex flex-1 justify-end gap-8">
            <div class="flex items-center gap-9">
              <a class="text-white text-sm font-medium leading-normal" href="#">Home</a>
              <a class="text-white text-sm font-medium leading-normal" href="#">Services</a>
              <a class="text-white text-sm font-medium leading-normal" href="#">About</a>
              <a class="text-white text-sm font-medium leading-normal" href="#">Contact</a>
            </div>
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#fcdd53] text-[#232010] text-sm font-bold leading-normal tracking-[0.015em]"
            >
              <span class="truncate">Book Now</span>
            </button>
          </div>
        </header>
        <div class="px-40 flex flex-1 justify-center py-5">
          <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
            <div class="flex flex-wrap justify-between gap-3 p-4">
              <div class="flex min-w-72 flex-col gap-3">
                <p class="text-white tracking-light text-[32px] font-bold leading-tight">Our Services</p>
                <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                  Explore our comprehensive range of vehicle inspection and maintenance services, designed to keep your vehicle in optimal condition. Each service is performed by
                  our certified technicians using state-of-the-art equipment.
                </p>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Inspection Services</h2>
            <div class="p-4">
              <div class="flex items-stretch justify-between gap-4 rounded-lg">
                <div class="flex flex-[2_2_0px] flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-white text-base font-bold leading-tight">Comprehensive Vehicle Inspection</p>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                      A thorough inspection covering all major components, including engine, brakes, suspension, and electrical systems. Receive a detailed report with
                      recommendations.
                    </p>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 flex-row-reverse bg-[#4a4321] text-white text-sm font-medium leading-normal w-fit"
                  >
                    <span class="truncate">$199 - 2 hours</span>
                  </button>
                </div>
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBd9htGa6cL_zpGjZzMbbQm_upMpOzepf_WnCB3mc9AhbS4JdSuGA49doR8rG-8jS3aPJNoKXetNtqm_UG2qFQJUao-5MrnG-BOyJnEHfPE3oqcY7reLqgrSX53BtSzsUUJzZAZXPv5TQNhEu7rP0budQUgkXoLMeN6H0DlB6bDLcuWINFaP2-fw9U0NfosSZLQoR-QynC18cKO5eKoRbNikwTFEfZutbLWp_iBi0iVUIic0D0uMKve6rApZ6M3wilJCVc2g7EVfOo");'
                ></div>
              </div>
            </div>
            <div class="p-4">
              <div class="flex items-stretch justify-between gap-4 rounded-lg">
                <div class="flex flex-[2_2_0px] flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-white text-base font-bold leading-tight">Pre-Purchase Inspection</p>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                      Ensure the vehicle you're buying is in top shape. We'll assess its condition and provide a detailed report to help you make an informed decision.
                    </p>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 flex-row-reverse bg-[#4a4321] text-white text-sm font-medium leading-normal w-fit"
                  >
                    <span class="truncate">$249 - 2.5 hours</span>
                  </button>
                </div>
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDk2Mle0hNeFutRppkYZIo7MzZHCl_LoUmBquF8V91AQcMnG_bqw5YVzuykOWRpQ1eUJ3trMTGa0jaSL74V9mAGAG1-g2VuJ4ok1rzZg5uFGDVNl0RnT3AX8b9JnctwmjIXSUsx4pLA-dN4fgQH6SJQiA13sSX37Ji91O37kR915tLuNZdCqLwXH-HF5GiQUdf_qVJs2ELE6rhwUNrO6sT1S_Kr9D6XaV5DmsJlruXLqSS7mRu_k2DIMq6zYniz9qUBMef0PFpCLIw");'
                ></div>
              </div>
            </div>
            <h2 class="text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Maintenance Services</h2>
            <div class="p-4">
              <div class="flex items-stretch justify-between gap-4 rounded-lg">
                <div class="flex flex-[2_2_0px] flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-white text-base font-bold leading-tight">Oil Change and Filter Replacement</p>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                      Keep your engine running smoothly with our professional oil change service. Includes high-quality oil and filter replacement.
                    </p>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 flex-row-reverse bg-[#4a4321] text-white text-sm font-medium leading-normal w-fit"
                  >
                    <span class="truncate">$99 - 1 hour</span>
                  </button>
                </div>
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC0pfAiONi5Ffvpsayi6VvB3nTIDK3aBNIbYZ7K2blZQlsW2jdHlXolVvub47YnactQBhAXg3_kRGTOOKeyIBrSziAHisdarLi3VeZzis5vMLi_UHyASKFzkBbHlzDpmIzoSiQ8zkmVwgkuZZL7QpWxJfMt8SdVJ464SLt6nhFtHNWryEtSV5TO3tzkoJwXur8DJ6Bhz2h8op5nSKMjQVAdglbrvXU1NOg2HvSHaSE9O9j5moAzsdM-NFRZ3svltHAZDPv96CpPE-w");'
                ></div>
              </div>
            </div>
            <div class="p-4">
              <div class="flex items-stretch justify-between gap-4 rounded-lg">
                <div class="flex flex-[2_2_0px] flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-white text-base font-bold leading-tight">Brake Service</p>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                      Ensure your safety with our comprehensive brake service. Includes inspection, pad replacement, and rotor resurfacing.
                    </p>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 flex-row-reverse bg-[#4a4321] text-white text-sm font-medium leading-normal w-fit"
                  >
                    <span class="truncate">$299 - 3 hours</span>
                  </button>
                </div>
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD5KXIvYvGO7feRNZ-R7ojir3fKe0HqLhWkRnrGU4T62gBgvPpWxONJ1xz_isrNbvGCvi1DmJG6S71uIsWFo1J_FSQr_4IhJZt0LaGElfhYvF3Yxm2Ub7iMVzxua4Ad2xzmIxbXZlEe_A93bJ5vIgri27Crgm08Xe7jUP3H32ZozeKl8DNclL4AHBUoXl-EH6Uo-fthyMuSowId8C35bhtZj3Da1jABxdA5oiJjf6u0XPb8Qqpn_DFUwoASw9Igfo5s1XVPj21dA4A");'
                ></div>
              </div>
            </div>
            <div class="p-4">
              <div class="flex items-stretch justify-between gap-4 rounded-lg">
                <div class="flex flex-[2_2_0px] flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-white text-base font-bold leading-tight">Tire Rotation and Balancing</p>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                      Extend the life of your tires and improve handling with our tire rotation and balancing service.
                    </p>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 flex-row-reverse bg-[#4a4321] text-white text-sm font-medium leading-normal w-fit"
                  >
                    <span class="truncate">$79 - 1.5 hours</span>
                  </button>
                </div>
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBj6iA7MgkI-VqAVHSfw6bG1yHB-RK65ngxAsb8UB1mkYMz0EB4-nt9SRZpADV82L8yXxprhoAKAmHSI7wzvlXi_tPZAgZnhRgmCxJVcuFteu9Zp4AQ-UfuTl29b6vEd1AqjfWAAY7IlhfQBgJ_Khi811UhyemG69ZDQjOI0TNa1_Sge8LpnmRMPYS9MlaQMIssULGeftZePMTRHeBRdoMbd_F9qg_lEGxJkIRbonNNYySwNT0s75Ci9bmHX7MARKJVLE8mJHH5-z8");'
                ></div>
              </div>
            </div>
            <div class="p-4">
              <div class="flex items-stretch justify-between gap-4 rounded-lg">
                <div class="flex flex-[2_2_0px] flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-white text-base font-bold leading-tight">Wheel Alignment</p>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">Improve your vehicle's handling and fuel efficiency with our precise wheel alignment service.</p>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 flex-row-reverse bg-[#4a4321] text-white text-sm font-medium leading-normal w-fit"
                  >
                    <span class="truncate">$129 - 1.5 hours</span>
                  </button>
                </div>
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDivxdPonskQy7knJ4KETZKzaSuQxU37qyXSKR3Ruz0mQKfmX5-38f9zRhU_xTzUbGCxtKpjfz6whKhZKQRff8lcM9QO8ABJm-0Oh1Q5ARg94lBVPSh18Y8f7IAHV9B-hvFdNRKxIDq8MipSv6UbQ9ESV15QQ4QOrzi10TfwMPmfpzJ-VVZ3OVDtyrHGFDpqUJgUjAxRgRR2fgaLljNEMsBgWYfd-SGUdbdaOtOSOeY8a1XbLR-RPXcN0lKhqu0aHeFOfRceF32R8Y");'
                ></div>
              </div>
            </div>
            <div class="p-4">
              <div class="flex items-stretch justify-between gap-4 rounded-lg">
                <div class="flex flex-[2_2_0px] flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-white text-base font-bold leading-tight">Battery Replacement</p>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                      Ensure reliable starting power with our battery replacement service. Includes installation and testing.
                    </p>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 flex-row-reverse bg-[#4a4321] text-white text-sm font-medium leading-normal w-fit"
                  >
                    <span class="truncate">$149 - 1 hour</span>
                  </button>
                </div>
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAWnywXwIsNbKiBcvKVvdobrDvchbM3zoG1aKCTc-20pWDo_cCHBtF-afz1mUPykrrZOVIN6Wjf7sp2I6BNGrsmatPDFlAqmP_RRZ3e--7fILmlNciHPNfxaU5XCgghAYpTvnFoSoaVo9betHL55WA4RxAKiELiefxO57JMYulgZHV4UOa8RW-_c13Z8OBZ89H--Etdu9sCwl-7iBbU5TYT_Acxi8Wwknhfuw1f3inhb5M7nIPvINlks5wjKBdoU7PTfc8-3gLvdRY");'
                ></div>
              </div>
            </div>
            <div class="p-4">
              <div class="flex items-stretch justify-between gap-4 rounded-lg">
                <div class="flex flex-[2_2_0px] flex-col gap-4">
                  <div class="flex flex-col gap-1">
                    <p class="text-white text-base font-bold leading-tight">Air Conditioning Service</p>
                    <p class="text-[#cdc28e] text-sm font-normal leading-normal">
                      Stay cool and comfortable with our air conditioning service. Includes inspection, refrigerant recharge, and leak detection.
                    </p>
                  </div>
                  <button
                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-8 px-4 flex-row-reverse bg-[#4a4321] text-white text-sm font-medium leading-normal w-fit"
                  >
                    <span class="truncate">$179 - 2 hours</span>
                  </button>
                </div>
                <div
                  class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-lg flex-1"
                  style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuA0h3IqvKSvyn33Z6dH_kSxOlS4rl0SA6p9tRQeTVLJ4q_yx23D0Sroi1rUyfwUry14V3HKdFdPIDKQDHQefhF4l4sxU5UlD3L0sFlbMBQjVIPdOZUWdOuvkLI4vV5Hcz_pVS8vn79mEH2Ffp-xM3v-HpV7gUgkVZmT66dFFTlYc9Z3W0XEr95Z04KrnhwGnr_Li_uM7fzvUnYeIuZIUHPauTm6EzdVSr4lvkLoZj7mBV3BYP9_L84HFChW_B0zgInGTdspucfqxds");'
                ></div>
              </div>
            </div>
            <div class="flex px-4 py-3 justify-center">
              <button
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-5 bg-[#fcdd53] text-[#232010] text-base font-bold leading-normal tracking-[0.015em]"
              >
                <span class="truncate">Book Now</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
