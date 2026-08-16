<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('STEM Career Chatbot')]
class CareerChatbot extends Component
{
    public string $studentName = '';

    public string $level = '';

    public string $favoriteSubject = '';

    public string $interest = '';

    public string $strength = '';

    public string $goal = '';

    public string $learningStyle = '';

    public string $message = '';

    /**
     * @var array<int, array{role: string, text: string}>
     */
    public array $messages = [];

    public bool $hasAssessment = false;

    public function mount(): void
    {
        $this->messages = [
            [
                'role' => 'bot',
                'text' => 'Hi! I’m your STEM career guide. Tell me a little about the student, and I’ll help map out a realistic path with the right subjects, skills, and next steps.',
            ],
        ];
    }

    public function startAssessment(): void
    {
        $this->validate([
            'studentName' => ['required', 'string', 'max:60'],
            'level' => ['required', 'string', 'max:80'],
            'favoriteSubject' => ['required', 'string', 'max:80'],
            'interest' => ['required', 'string', 'max:80'],
            'strength' => ['required', 'string', 'max:80'],
            'goal' => ['required', 'string', 'max:120'],
            'learningStyle' => ['required', 'string', 'max:80'],
        ]);

        $this->hasAssessment = true;
        $this->messages[] = [
            'role' => 'user',
            'text' => "Profile: {$this->studentName}, {$this->level}. Best subject: {$this->favoriteSubject}. Interest: {$this->interest}. Strength: {$this->strength}. Goal: {$this->goal}. Learning style: {$this->learningStyle}.",
        ];

        $this->think();

        $this->messages[] = [
            'role' => 'bot',
            'text' => $this->buildRecommendationResponse(),
        ];
    }

    public function sendMessage(): void
    {
        $this->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = trim($this->message);

        $this->messages[] = [
            'role' => 'user',
            'text' => $message,
        ];

        $this->think();

        $this->messages[] = [
            'role' => 'bot',
            'text' => $this->buildChatResponse($message),
        ];

        $this->message = '';
    }

    public function ask(string $question): void
    {
        $this->message = $question;
        $this->sendMessage();
    }

    public function fillSample(string $track): void
    {
        $samples = [
            'software' => ['Ada', 'SS3 / Grade 12', 'Computer Science', 'Building apps and websites', 'Logical thinking', 'Get admission into Software Engineering', 'Hands-on projects'],
            'health' => ['Musa', 'SS2 / Grade 11', 'Biology', 'Health and laboratory work', 'Research and observation', 'Work in medical science', 'Reading and research'],
            'engineering' => ['Tomi', '100 Level', 'Physics', 'Machines and robotics', 'Practical repair work', 'Build physical technology products', 'Hands-on projects'],
        ];

        [$this->studentName, $this->level, $this->favoriteSubject, $this->interest, $this->strength, $this->goal, $this->learningStyle] = $samples[$track] ?? $samples['software'];
    }

    public function resetChat(): void
    {
        $this->reset(['studentName', 'level', 'favoriteSubject', 'interest', 'strength', 'goal', 'learningStyle', 'message', 'hasAssessment']);
        $this->mount();
    }

    #[Computed]
    public function topCareers(): array
    {
        $profile = $this->profileText();

        return collect($this->careerPaths())
            ->map(function (array $career) use ($profile) {
                $score = 0;
                $reasons = [];

                foreach ($career['weights'] as $keyword => $weight) {
                    if (str_contains($profile, $keyword)) {
                        $score += $weight;
                        $reasons[] = $this->humanizeKeyword($keyword);
                    }
                }

                $match = min(98, max(42, 46 + ($score * 7)));

                return [
                    ...$career,
                    'score' => $score,
                    'match' => $match,
                    'reasons' => array_slice(array_values(array_unique($reasons)), 0, 4),
                ];
            })
            ->sortByDesc('score')
            ->values()
            ->take(3)
            ->all();
    }

    #[Computed]
    public function primaryCareer(): array
    {
        return $this->topCareers[0] ?? $this->careerPaths()[0];
    }

    #[Computed]
    public function readinessScore(): int
    {
        if (! $this->hasAssessment) {
            return 0;
        }

        $career = $this->primaryCareer;
        $profile = $this->profileText();
        $skillHits = collect($career['skills'])->filter(fn (string $skill): bool => str_contains($profile, strtolower($skill)))->count();
        $base = 52 + ($career['score'] * 6) + ($skillHits * 4);

        return min(96, max(48, $base));
    }

    #[Computed]
    public function roadmap(): array
    {
        $career = $this->primaryCareer;

        return [
            [
                'period' => 'This month',
                'title' => 'Build foundation',
                'tasks' => ['Revise '.$career['subjects'][0], 'Watch two beginner lessons', 'Write a one-page career note'],
            ],
            [
                'period' => 'Next 3 months',
                'title' => 'Create evidence',
                'tasks' => [$career['projects'][0], 'Document screenshots and results', 'Ask a teacher or mentor for feedback'],
            ],
            [
                'period' => 'Before admission',
                'title' => 'Prepare the pathway',
                'tasks' => ['Compare related university courses', 'List entry subject requirements', 'Practice interview or presentation questions'],
            ],
        ];
    }

    #[Computed]
    public function profileSummary(): string
    {
        if (! $this->hasAssessment) {
            return 'Waiting for student profile';
        }

        return "{$this->studentName} is strongest in {$this->favoriteSubject}, prefers {$this->learningStyle}, and wants to {$this->goal}.";
    }

    private function buildRecommendationResponse(): string
    {
        $career = $this->primaryCareer;
        $reasons = empty($career['reasons']) ? 'your current subject and interest profile' : implode(', ', $career['reasons']);
        $student = trim($this->studentName) !== '' ? $this->studentName : 'this student';

        return "Nice work, {$student}. Based on the profile, the strongest fit is {$career['title']} at about {$career['match']}%. I can see this match because your interests line up with {$reasons}. A practical next step is to start with {$career['projects'][0]}, then build confidence in {$career['skills'][0]} and {$career['skills'][1]} while improving {$career['subjects'][0]}.";
    }

    private function buildChatResponse(string $message): string
    {
        $normalized = strtolower($message);
        $career = $this->primaryCareer;

        if (! $this->hasAssessment) {
            return 'I can help with that, but I need the student profile first. Once you fill in the details, I can suggest the best STEM path, subjects, and a realistic plan.';
        }

        if (preg_match('/\b(hello|hi|hey|good morning|good afternoon|greetings)\b/', $normalized)) {
            return "Hi! I’ve got your profile, and I’d say the best direction right now is {$career['title']}. Want me to explain why it fits or show you the first steps?";
        }

        if (preg_match('/\b(thank you|thanks|appreciate it)\b/', $normalized)) {
            return "You’re welcome. I’m happy to help. If you want, I can also turn this into a short 30-day plan for the student.";
        }

        if (preg_match('/\b(roadmap|plan|next step|what should i do next|steps|schedule)\b/', $normalized)) {
            return "Here’s a natural next step for {$career['title']}: start by strengthening {$career['subjects'][0]}, then complete {$career['projects'][0]}, and finally prepare for {$career['degrees'][0]} or {$career['degrees'][1]}. That progression keeps the plan realistic and focused.";
        }

        if (preg_match('/\b(skill|skills|learn|study|improve|practice)\b/', $normalized)) {
            return "For {$career['title']}, I’d focus on ".implode(', ', $career['skills']).". Since the student prefers {$this->learningStyle}, practical tasks are likely more effective than only reading theory, so I’d start with one quick project and build from there.";
        }

        if (preg_match('/\b(subject|course|subjects|class)\b/', $normalized)) {
            return "The most relevant subjects for {$career['title']} are ".implode(', ', $career['subjects']).". If the student wants to go further, related courses include ".implode(', ', $career['degrees']).'.';
        }

        if (preg_match('/\b(project|portfolio|build|idea)\b/', $normalized)) {
            return "A good starting project for {$career['title']} would be {$career['projects'][0]}. It gives the student something concrete to show, helps with confidence, and makes the career path feel real.";
        }

        if (preg_match('/\b(why|match|fit|best)\b/', $normalized)) {
            return "The match makes sense because the student seems to enjoy ".implode(', ', $career['reasons'] ?: ['your selected subject, interest, strength, and goal']).". That combination points strongly toward {$career['title']} and gives a clear reason for the recommendation.";
        }

        if (preg_match('/\b(who are you|what can you do|help)\b/', $normalized)) {
            return "I’m a guidance assistant for STEM students. I can explain the best-fit career, suggest subjects and skills, recommend a project, and create a realistic next-step plan based on the student’s profile.";
        }

        if (preg_match('/\b(advice|suggest|recommend)\b/', $normalized)) {
            return $this->buildRecommendationResponse();
        }

        return "I’d say the student is trending toward {$career['title']}. The strongest signal is {$career['why']}, and the next step is to turn that interest into action with a small project plus a few focused study goals. Want me to help build that plan?";
    }

    private function think(): void
    {
        //
    }

    private function profileText(): string
    {
        return strtolower($this->favoriteSubject.' '.$this->interest.' '.$this->strength.' '.$this->goal.' '.$this->learningStyle);
    }

    private function humanizeKeyword(string $keyword): string
    {
        return match ($keyword) {
            'math' => 'mathematics',
            'app' => 'app building',
            'lab' => 'laboratory work',
            'robot' => 'robotics',
            default => str_replace('-', ' ', $keyword),
        };
    }

    private function careerPaths(): array
    {
        return [
            [
                'title' => 'Software Developer',
                'field' => 'Digital Technology',
                'summary' => 'Designs and builds web, mobile, and business applications.',
                'why' => 'you enjoy computing, logic, building apps, or solving problems with code',
                'weights' => ['computer' => 3, 'app' => 4, 'website' => 4, 'software' => 5, 'logic' => 3, 'hands-on' => 2, 'engineering' => 2],
                'skills' => ['Programming', 'Databases', 'Web development', 'Debugging'],
                'subjects' => ['Computer Science', 'Mathematics', 'Data Processing'],
                'degrees' => ['Software Engineering', 'Computer Science', 'Information Technology'],
                'projects' => ['Build a student result checker', 'Create a school event booking app', 'Design a simple inventory system'],
                'tools' => ['PHP/Laravel', 'JavaScript', 'MySQL'],
            ],
            [
                'title' => 'Data Scientist',
                'field' => 'Data and Analytics',
                'summary' => 'Uses data to discover patterns, predict outcomes, and support decisions.',
                'why' => 'you like mathematics, patterns, research, statistics, or decision making with data',
                'weights' => ['math' => 4, 'mathematics' => 4, 'data' => 5, 'analysis' => 5, 'research' => 3, 'statistics' => 5, 'reading' => 2],
                'skills' => ['Statistics', 'Python', 'Data visualization', 'Critical thinking'],
                'subjects' => ['Mathematics', 'Further Mathematics', 'Computer Science'],
                'degrees' => ['Data Science', 'Statistics', 'Computer Science'],
                'projects' => ['Analyze student performance trends', 'Build a simple admission prediction model', 'Create a survey dashboard'],
                'tools' => ['Python', 'Excel', 'Power BI'],
            ],
            [
                'title' => 'Electrical Engineer',
                'field' => 'Engineering Systems',
                'summary' => 'Works with electronics, power systems, circuits, and automation.',
                'why' => 'you are drawn to electronics, circuits, power systems, and practical physics',
                'weights' => ['physics' => 4, 'electric' => 5, 'electronics' => 5, 'circuit' => 5, 'repair' => 3, 'hands-on' => 2],
                'skills' => ['Circuit design', 'Physics', 'Troubleshooting', 'Technical drawing'],
                'subjects' => ['Physics', 'Mathematics', 'Technical Drawing'],
                'degrees' => ['Electrical Engineering', 'Computer Engineering', 'Mechatronics'],
                'projects' => ['Build an automatic light controller', 'Create a battery level monitor', 'Design a small solar charging prototype'],
                'tools' => ['Arduino', 'Multimeter', 'Proteus'],
            ],
            [
                'title' => 'Mechanical Engineer',
                'field' => 'Product and Machines',
                'summary' => 'Designs machines, mechanical systems, manufacturing processes, and physical products.',
                'why' => 'you like machines, design, manufacturing, and solving physical problems',
                'weights' => ['machine' => 5, 'mechanical' => 5, 'robot' => 4, 'robotics' => 4, 'design' => 3, 'physics' => 3, 'drawing' => 3, 'hands-on' => 2],
                'skills' => ['CAD design', 'Mechanics', 'Materials', 'Project planning'],
                'subjects' => ['Physics', 'Mathematics', 'Technical Drawing'],
                'degrees' => ['Mechanical Engineering', 'Mechatronics', 'Industrial Engineering'],
                'projects' => ['Design a manual water pump model', 'Build a small robotic arm prototype', 'Create a 3D model of a machine part'],
                'tools' => ['Fusion 360', 'AutoCAD', 'Arduino'],
            ],
            [
                'title' => 'Biomedical Scientist',
                'field' => 'Health Science',
                'summary' => 'Uses laboratory science to support diagnosis, health research, and medical innovation.',
                'why' => 'you enjoy biology, chemistry, laboratory work, and health-related research',
                'weights' => ['biology' => 5, 'chemistry' => 4, 'health' => 5, 'medical' => 5, 'lab' => 5, 'laboratory' => 5, 'research' => 3, 'reading' => 2],
                'skills' => ['Laboratory practice', 'Scientific writing', 'Observation', 'Research methods'],
                'subjects' => ['Biology', 'Chemistry', 'Physics'],
                'degrees' => ['Biomedical Science', 'Medical Laboratory Science', 'Biotechnology'],
                'projects' => ['Prepare a health awareness data survey', 'Create a lab safety learning guide', 'Research common water contamination risks'],
                'tools' => ['Microscope', 'Spreadsheet', 'Research journals'],
            ],
            [
                'title' => 'Environmental Scientist',
                'field' => 'Sustainability',
                'summary' => 'Studies climate, water, land, agriculture, and environmental protection.',
                'why' => 'you care about climate, agriculture, water, energy, and solving community problems',
                'weights' => ['environment' => 5, 'climate' => 5, 'agriculture' => 4, 'water' => 4, 'energy' => 3, 'community' => 3, 'biology' => 2, 'chemistry' => 2],
                'skills' => ['Field research', 'Data collection', 'Environmental monitoring', 'Report writing'],
                'subjects' => ['Biology', 'Chemistry', 'Geography'],
                'degrees' => ['Environmental Science', 'Agricultural Engineering', 'Geography'],
                'projects' => ['Map waste disposal points around school', 'Test and compare water samples', 'Create a climate awareness dashboard'],
                'tools' => ['Google Forms', 'Spreadsheet', 'Maps'],
            ],
        ];
    }
}
