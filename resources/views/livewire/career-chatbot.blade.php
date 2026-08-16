<div class="career-layout mx-auto grid gap-7 px-5 pb-12 pt-10 lg:grid-cols-[.78fr_1.22fr] lg:px-12 lg:pt-14">
    <div class="space-y-7">
        <div class="px-1">
            <div class="flex items-center justify-between">
                <p class="career-kicker">Student pathway / 01</p>
                <span class="career-number">2026 edition</span>
            </div>
            <h1 class="career-display mt-7 max-w-xl">A clearer route<br><span class="text-[#f27a5b]">into what's next.</span></h1>
            <p class="mt-6 max-w-lg text-sm leading-7 text-[#68757a]">Build a practical STEM direction from the things a student already enjoys. No black box, no noise, just an explainable starting point.</p>
            <div class="mt-8 flex items-center gap-3 text-xs font-semibold"><span class="h-px w-10 bg-[#f27a5b]"></span>Seven signals. One useful direction.</div>
        </div>

        <form wire:submit="startAssessment" class="career-panel bg-white p-5 sm:p-7">
            <div class="flex items-start justify-between gap-4 border-b border-[#dedbd4] pb-5">
                <div><p class="career-kicker">Profile builder</p><h2 class="mt-2 text-xl font-extrabold tracking-tight">Tell us about the learner</h2></div>
                <button type="button" wire:click="fillSample('software')" class="text-xs font-bold text-[#f27a5b] underline underline-offset-4">Use sample</button>
            </div>

            <div class="mt-6 space-y-4">
                <div><label for="studentName" class="career-label">01 / Name</label><input id="studentName" wire:model="studentName" type="text" class="career-field mt-2" placeholder="Student name">@error('studentName') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror</div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label for="level" class="career-label">02 / Current level</label><select id="level" wire:model="level" class="career-field mt-2"><option value="">Choose level</option><option>SS1 / Grade 10</option><option>SS2 / Grade 11</option><option>SS3 / Grade 12</option><option>100 Level</option><option>200 Level</option></select>@error('level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror</div>
                    <div><label for="favoriteSubject" class="career-label">03 / Best subject</label><select id="favoriteSubject" wire:model="favoriteSubject" class="career-field mt-2"><option value="">Choose subject</option><option>Mathematics</option><option>Computer Science</option><option>Physics</option><option>Chemistry</option><option>Biology</option><option>Technical Drawing</option></select>@error('favoriteSubject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror</div>
                </div>
                <div><label for="interest" class="career-label">04 / What pulls them in</label><select id="interest" wire:model="interest" class="career-field mt-2"><option value="">Choose an interest</option><option>Building apps and websites</option><option>Solving mathematics problems</option><option>Electronics and circuits</option><option>Machines and robotics</option><option>Health and laboratory work</option><option>Climate and environment</option></select>@error('interest') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror</div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label for="strength" class="career-label">05 / Natural strength</label><select id="strength" wire:model="strength" class="career-field mt-2"><option value="">Choose strength</option><option>Logical thinking</option><option>Creativity and design</option><option>Research and observation</option><option>Practical repair work</option><option>Communication</option><option>Data analysis</option></select>@error('strength') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror</div>
                    <div><label for="learningStyle" class="career-label">06 / Best way to learn</label><select id="learningStyle" wire:model="learningStyle" class="career-field mt-2"><option value="">Choose style</option><option>Hands-on projects</option><option>Reading and research</option><option>Video lessons</option><option>Group learning</option></select>@error('learningStyle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror</div>
                </div>
                <div><label for="goal" class="career-label">07 / The ambition</label><input id="goal" wire:model="goal" type="text" placeholder="What would they love to become or build?" class="career-field mt-2">@error('goal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror</div>
            </div>
            <div class="mt-7 grid gap-3 sm:grid-cols-[1fr_auto]"><button type="submit" wire:loading.attr="disabled" wire:target="startAssessment" class="career-button career-button-accent">Reveal my pathway <span class="ml-3 text-base">↗</span></button><button type="button" wire:click="resetChat" class="career-button career-button-quiet">Clear</button></div>
        </form>
    </div>

    <div class="space-y-7">
        <div class="career-panel bg-white p-5 sm:p-7">
            <div class="flex items-start justify-between gap-5"><div><p class="career-kicker">Your signal map</p><h2 class="mt-2 text-3xl font-extrabold tracking-tight">{{ $this->hasAssessment ? $this->primaryCareer['title'] : 'Your path is waiting' }}</h2><p class="mt-3 max-w-2xl text-sm leading-6 text-[#68757a]">{{ $this->hasAssessment ? $this->primaryCareer['summary'] : 'Complete the profile and we’ll translate interests into a focused STEM direction.' }}</p></div><div class="shrink-0 text-right"><p class="font-mono text-4xl font-medium text-[#f27a5b]">{{ $this->hasAssessment ? $this->primaryCareer['match'] : 0 }}%</p><p class="career-label">fit score</p></div></div>
            <div class="mt-6 h-2 bg-[#eeeae1]"><div class="h-full bg-[#c7e86a] transition-all" style="width: {{ $this->hasAssessment ? $this->primaryCareer['match'] : 0 }}%"></div></div>
            <div class="mt-6 grid gap-px overflow-hidden border border-[#dedbd4] bg-[#dedbd4] md:grid-cols-3"><div class="bg-[#faf9f5] p-4"><p class="career-label">Focus subjects</p><p class="mt-2 text-sm font-bold">{{ implode(', ', $this->primaryCareer['subjects']) }}</p></div><div class="bg-[#faf9f5] p-4"><p class="career-label">Useful tools</p><p class="mt-2 text-sm font-bold">{{ implode(', ', $this->primaryCareer['tools']) }}</p></div><div class="bg-[#faf9f5] p-4"><p class="career-label">First project</p><p class="mt-2 text-sm font-bold">{{ $this->primaryCareer['projects'][0] }}</p></div></div>
        </div>

        <div class="career-chat flex min-h-147.5 flex-col overflow-hidden">
            <div class="flex items-start justify-between border-b border-white/10 p-5 sm:p-7"><div><p class="career-kicker text-[#c7e86a]!">Open conversation</p><h2 class="mt-2 text-2xl font-extrabold tracking-tight">Ask the advisor.</h2></div><div class="text-right"><span class="inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-[#9ba8a6]"><span class="h-2 w-2 rounded-full bg-[#c7e86a]"></span>Live</span><p class="mt-2 text-xs text-[#82908d]">Rule-based guidance</p></div></div>
            <div class="flex-1 space-y-4 overflow-y-auto p-5 sm:p-7">
                @foreach ($messages as $chat)
                    <div class="flex {{ $chat['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[88%] px-4 py-3 text-sm leading-6 {{ $chat['role'] === 'user' ? 'career-chat-user' : 'career-chat-bot' }}">{{ $chat['text'] }}</div>
                    </div>
                @endforeach
                <div wire:loading.delay wire:target="startAssessment,sendMessage,ask" class="flex justify-start">
                    <div class="career-chat-bot px-4 py-3 text-sm"><span class="font-mono text-xs">Preparing guidance...</span></div>
                </div>
            </div>
            <div class="border-t border-white/10 p-5 sm:p-7">
                <div class="mb-4 flex flex-wrap gap-2">
                    <button type="button" wire:click="ask('Why this match?')" class="career-chip">Why this match?</button>
                    <button type="button" wire:click="ask('What skills should I learn?')" class="career-chip">Skills</button>
                    <button type="button" wire:click="ask('Suggest a project')" class="career-chip">Project</button>
                    <button type="button" wire:click="ask('Show me a roadmap')" class="career-chip">Roadmap</button>
                </div>
                <form wire:submit="sendMessage" class="flex gap-2">
                    <input wire:model="message" type="text" placeholder="Ask about the next step..." class="min-w-0 flex-1 rounded-[3px] border-0 bg-[#f8f6f1] px-3 py-3 text-sm text-[#18232b] outline-none focus:ring-2 focus:ring-[#c7e86a]">
                    <button type="submit" wire:loading.attr="disabled" wire:target="sendMessage" class="career-button career-button-accent px-5">Send <span class="ml-2">↗</span></button>
                </form>
                @error('message') <p class="mt-2 text-xs text-red-300">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>
