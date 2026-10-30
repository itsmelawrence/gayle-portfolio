<?php

/*
|--------------------------------------------------------------------------
| Portfolio content
|--------------------------------------------------------------------------
|
| Everything the home page shows. Text in [square brackets] renders as a
| highlighted placeholder (dashed underline) until it is replaced.
|
*/

return [

    'name' => env('PORTFOLIO_NAME', 'Gayle'),

    // Inquiries from the contact form are emailed here. Leave unset to show a placeholder.
    'contact' => [
        'email' => env('PORTFOLIO_EMAIL'),
        'linkedin' => env('PORTFOLIO_LINKEDIN'),
        'instagram' => env('PORTFOLIO_INSTAGRAM'),
        'location' => env('PORTFOLIO_LOCATION'),
        'response_time' => env('PORTFOLIO_RESPONSE_TIME'),
    ],

    'categories' => [
        'paid' => 'Paid advertising',
        'research' => 'Competitor ad research',
        'leadgen' => 'Lead generation',
        'video' => 'Video & creative',
        'social' => 'Social media',
        'funnel' => 'Funnels & landing pages',
        'crm' => 'CRM & automation',
    ],

    'results_placeholder' => '[Results will be added once verified. Leave this blank rather than estimating.]',

    'projects' => [
        [
            'slug' => 'case-ad-publishing',
            'cat' => 'paid',
            'mock' => 'ad',
            'tone' => 'plum',
            'title' => 'Publishing Meta campaigns for DTC brands',
            'client' => 'Client A, DTC advertising agency',
            'industry' => 'Direct-to-consumer brands',
            'overview' => 'Ongoing ad publishing and execution support for an agency that runs Meta campaigns on behalf of direct-to-consumer brands.',
            'objective' => 'Get campaigns live accurately and on schedule, so the agency’s strategy reaches the market as planned.',
            'role' => 'Ad publisher and advertising research support',
            'challenges' => '[Describe a real challenge, e.g. volume of launches or number of brand accounts.]',
            'approach' => [
                'Publish and execute campaigns in Meta Business Suite.',
                'Support advertising execution across the agency’s DTC brand accounts.',
                '[Add a specific step from your process.]',
            ],
            'tools' => ['Meta Business Suite'],
            'deliverables' => ['Published campaigns', '[Add deliverables]'],
            'size' => 'w7',
        ],
        [
            'slug' => 'case-competitor-research',
            'cat' => 'research',
            'mock' => 'grid',
            'tone' => 'surface',
            'title' => 'Competitor ad research for creative direction',
            'client' => 'Client A, DTC advertising agency',
            'industry' => 'Direct-to-consumer brands',
            'overview' => 'Research into competitor advertising to find creative angles and concepts the agency’s brands can use as reference.',
            'objective' => 'Give the creative team a clear view of what competitors are running and which angles are worth using.',
            'role' => 'Advertising research support',
            'challenges' => '[Describe a real challenge.]',
            'approach' => [
                'Research competitor ads from [sources used].',
                'Analyse creatives for hooks, angles, formats, and messaging.',
                'Identify concepts that can serve as references for brands.',
            ],
            'tools' => ['[Research tools used]'],
            'deliverables' => ['Creative angle references', '[Research document or board format]'],
            'size' => 'w5',
        ],
        [
            'slug' => 'case-lead-gen',
            'cat' => 'leadgen',
            'mock' => 'form',
            'tone' => 'mauve',
            'title' => 'Lead generation campaigns for service businesses',
            'client' => 'Client B, paid advertising & lead generation',
            'industry' => 'Marketing, bookkeeping, and automotive detailing',
            'overview' => 'Structuring, setting up, and managing Meta lead generation campaigns for service businesses across three industries.',
            'objective' => 'Generate enquiries for service businesses through clearly structured Meta campaigns.',
            'role' => 'Paid advertising specialist',
            'challenges' => '[Describe a real challenge, e.g. adapting one approach to three very different industries.]',
            'approach' => [
                'Structure each campaign around the business’s objective.',
                'Set up and manage campaigns in Meta.',
                'Research competitor ads to inform creative.',
                'Support campaign strategy and execution.',
            ],
            'tools' => ['Meta Business Suite'],
            'deliverables' => [
                'Campaign structures',
                'Live lead generation campaigns',
                '[Add deliverables]',
            ],
            'size' => 'w4',
        ],
        [
            'slug' => 'case-video-ads',
            'cat' => 'video',
            'mock' => 'phone',
            'tone' => 'rose',
            'title' => 'Short-form video ads',
            'client' => 'Client B, paid advertising & lead generation',
            'industry' => 'Marketing, bookkeeping, and automotive detailing',
            'overview' => 'Editing short-form video ads and producing creatives built around each business’s objective.',
            'objective' => 'Give each campaign creative that fits the offer, the audience, and the format.',
            'role' => 'Creative support and video editing',
            'challenges' => '[Describe a real challenge.]',
            'approach' => [
                'Research competitor ads and creative inspiration before editing.',
                'Edit short-form video ads.',
                'Produce static ad creatives.',
            ],
            'tools' => ['CapCut', 'Canva'],
            'deliverables' => ['Short-form video ads', 'Static ad creatives'],
            'size' => 'w4',
        ],
        [
            'slug' => 'case-social',
            'cat' => 'social',
            'mock' => 'feed',
            'tone' => 'plum',
            'title' => 'Social media management',
            'client' => 'Client C, digital marketing & operations',
            'industry' => '[Industry]',
            'overview' => 'Managing the client’s social media accounts, from planning content to producing and publishing it.',
            'objective' => 'Keep the brand’s social presence consistent, organised, and on schedule.',
            'role' => 'Digital marketing and operations support',
            'challenges' => '[Describe a real challenge.]',
            'approach' => [
                'Plan content [weekly or monthly].',
                'Produce content, including video edits.',
                'Publish and organise content across [platforms].',
            ],
            'tools' => ['Social media platforms', 'Canva', 'CapCut'],
            'deliverables' => ['Content plans', 'Published content', '[Add deliverables]'],
            'size' => 'w4',
        ],
        [
            'slug' => 'case-funnels',
            'cat' => 'funnel',
            'mock' => 'browser',
            'tone' => 'surface',
            'title' => 'Funnels and landing pages',
            'client' => 'Client C, digital marketing & operations',
            'industry' => '[Industry]',
            'overview' => 'Building marketing funnels and landing pages that support the client’s acquisition.',
            'objective' => 'Give leads a clear page to land on and a clear next step to take.',
            'role' => 'Digital marketing and operations support',
            'challenges' => '[Describe a real challenge.]',
            'approach' => [
                'Build landing pages and funnels in GoHighLevel.',
                'Connect lead capture to the CRM.',
                '[Describe page structure and goals.]',
            ],
            'tools' => ['GoHighLevel'],
            'deliverables' => ['Landing pages', 'Funnels', '[Add deliverables]'],
            'size' => 'w6',
        ],
        [
            'slug' => 'case-crm-automation',
            'cat' => 'crm',
            'mock' => 'flow',
            'tone' => 'mauve',
            'title' => 'GoHighLevel CRM and workflow automation',
            'client' => 'Client C, digital marketing & operations',
            'industry' => '[Industry]',
            'overview' => 'Setting up GoHighLevel, organising pipelines and lead journeys, and building workflow automations.',
            'objective' => 'Make sure every lead is tracked, followed up, and moved forward without manual chasing.',
            'role' => 'Digital marketing and operations support',
            'challenges' => '[Describe a real challenge.]',
            'approach' => [
                'Set up GoHighLevel for the business.',
                'Organise CRM pipelines and lead journeys.',
                'Build workflow automations for [follow-up, appointments, or email].',
            ],
            'tools' => ['GoHighLevel'],
            'deliverables' => ['CRM pipelines', 'Workflow automations', '[Add deliverables]'],
            'size' => 'w6',
        ],
    ],

    'stages' => [
        [
            'name' => 'Research',
            'headline' => 'Study what is already working.',
            'body' => 'Before anything is made, I look at what competitors are running: the hooks, angles, and formats that keep showing up, and the gaps nobody has used yet.',
            'items' => ['Competitor ad research', 'Creative angle analysis', 'Concept references'],
        ],
        [
            'name' => 'Creative',
            'headline' => 'Turn angles into ads.',
            'body' => 'Research becomes short-form video edits and static creatives built around the campaign’s objective. The same production feeds organic social content.',
            'items' => ['Short-form video editing', 'Static ad creatives', 'Social content'],
        ],
        [
            'name' => 'Launch',
            'headline' => 'Structure and publish campaigns.',
            'body' => 'Campaigns are structured around one clear goal, then set up and published in Meta, with the details checked before anything goes live.',
            'items' => ['Meta Ads setup', 'Campaign structure', 'Lead generation campaigns'],
        ],
        [
            'name' => 'Capture',
            'headline' => 'Give every lead somewhere to land.',
            'body' => 'Landing pages and funnels in GoHighLevel turn ad clicks into named leads inside a CRM, rather than form fills that disappear into an inbox.',
            'items' => ['Landing pages', 'Funnels', 'Lead capture'],
        ],
        [
            'name' => 'Follow-up',
            'headline' => 'Make sure no lead goes cold.',
            'body' => 'Pipelines, lead journeys, and automated follow-up keep each lead moving, so the business spends its time on conversations instead of chasing.',
            'items' => ['CRM pipelines', 'Workflow automation', 'Email and appointment workflows'],
        ],
    ],

    'services' => [
        [
            'title' => 'Paid advertising & lead generation',
            'stage' => 'Launch',
            'summary' => 'Meta campaigns structured around a clear goal, from setup through publishing and ongoing management.',
            'includes' => [
                'Meta Ads campaign setup',
                'lead generation campaigns',
                'campaign structuring',
                'ad publishing and management',
                'competitor ad research',
                'creative testing support',
            ],
        ],
        [
            'title' => 'Creative research & production',
            'stage' => 'Research',
            'summary' => 'Ads grounded in research: what competitors run, which angles work, and creative produced to match.',
            'includes' => [
                'competitor ad research',
                'creative concept research',
                'short-form video editing',
                'video ad production',
                'static ad creatives',
                'Canva and CapCut production',
            ],
        ],
        [
            'title' => 'Social media management',
            'stage' => 'Creative',
            'summary' => 'Consistent, organised social channels, from the content plan through production and publishing.',
            'includes' => [
                'content planning',
                'content creation',
                'video editing',
                'publishing',
                'content organisation',
            ],
        ],
        [
            'title' => 'Funnel & CRM development',
            'stage' => 'Capture',
            'summary' => 'Landing pages, funnels, and GoHighLevel pipelines that turn clicks into leads you can see and manage.',
            'includes' => [
                'GoHighLevel setup',
                'landing pages',
                'funnel building',
                'lead capture systems',
                'CRM pipeline setup',
                'lead journey organisation',
            ],
        ],
        [
            'title' => 'Marketing automation',
            'stage' => 'Follow-up',
            'summary' => 'Workflows that follow up, remind, and nurture automatically, so leads move forward without manual chasing.',
            'includes' => [
                'workflow automation',
                'automated lead follow-up',
                'appointment workflows',
                'email automation',
                'lead nurturing',
                'business process automation',
            ],
        ],
    ],

    'engagements' => [
        [
            'client' => 'Client A',
            'sector' => 'DTC advertising agency',
            'role' => 'Ad publisher and advertising research support',
            'tasks' => [
                'Publishing and executing ad campaigns',
                'Researching competitor advertisements',
                'Analysing creatives for usable angles and concepts',
                'Supporting execution for direct-to-consumer brands',
            ],
            'strengths' => 'Meta ad operations, creative analysis, attention to detail',
        ],
        [
            'client' => 'Client B',
            'sector' => 'Paid advertising & lead generation',
            'role' => 'Paid advertising specialist and creative support',
            'tasks' => [
                'Structuring lead generation campaigns',
                'Setting up and managing Meta campaigns',
                'Editing short-form video ads',
                'Developing creatives around business objectives',
            ],
            'strengths' => 'Lead generation, campaign structure, video editing',
        ],
        [
            'client' => 'Client C',
            'sector' => 'Digital marketing & operations',
            'role' => 'Digital marketing and operations support',
            'tasks' => [
                'Managing and producing social media content',
                'Building funnels and landing pages',
                'Setting up GoHighLevel and its automations',
                'Organising lead journeys and CRM processes',
            ],
            'strengths' => 'Funnels, CRM, automation, process organisation',
        ],
    ],

    'traits' => [
        'Independent' => 'Takes projects from planning to launch without needing step-by-step direction.',
        'Adaptable' => 'Moves between ads, creative, social, and technical setup across different industries.',
        'Execution-focused' => 'Turns plans into live campaigns, creatives, funnels, and workflows.',
        'Resourceful' => 'Uses research and AI tools to solve problems and work more efficiently.',
        'Detail-oriented' => 'Cares about clean campaign structure, tidy workflows, and accurate data.',
        'Always learning' => 'Keeps picking up new tools and sharpening the craft.',
    ],

    'tools' => [
        'Advertising' => ['Meta Business Suite'],
        'CRM & automation' => ['GoHighLevel'],
        'Social media' => ['Social media platforms'],
        'Creative production' => ['CapCut', 'Canva'],
        'Communication & calling' => ['Slack', 'Kixie', 'Mango'],
        'AI & productivity' => ['ChatGPT', 'Gemini', 'Claude', 'Microsoft Office'],
    ],

];
