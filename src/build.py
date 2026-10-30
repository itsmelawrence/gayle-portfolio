# Generates index.html for the portfolio. Edit PROJECTS / content here, then run: python3 build.py
import html

NAME = "Gayle"

def ph(text):
    """Wrap placeholder text so it is visibly marked for replacement."""
    return f'<span class="ph">{html.escape(text)}</span>'

def fmt(text):
    """Render [bracketed] fragments as placeholders inside otherwise plain text."""
    out, buf, i = [], "", 0
    while i < len(text):
        if text[i] == "[":
            j = text.find("]", i)
            out.append(html.escape(buf)); buf = ""
            out.append(ph(text[i:j + 1])); i = j + 1
        else:
            buf += text[i]; i += 1
    out.append(html.escape(buf))
    return "".join(out)

CATS = [
    ("paid", "Paid advertising"),
    ("research", "Competitor ad research"),
    ("leadgen", "Lead generation"),
    ("video", "Video & creative"),
    ("social", "Social media"),
    ("funnel", "Funnels & landing pages"),
    ("crm", "CRM & automation"),
]
CAT = dict(CATS)

A = "Client A, DTC advertising agency"
B = "Client B, paid advertising & lead generation"
C = "Client C, digital marketing & operations"
RESULTS = "[Results will be added once verified. Leave this blank rather than estimating.]"

PROJECTS = [
    dict(slug="case-ad-publishing", cat="paid", mock="ad", tone="plum",
         title="Publishing Meta campaigns for DTC brands", client=A,
         industry="Direct-to-consumer brands",
         overview="Ongoing ad publishing and execution support for an agency that runs Meta campaigns on behalf of direct-to-consumer brands.",
         objective="Get campaigns live accurately and on schedule, so the agency's strategy reaches the market as planned.",
         role="Ad publisher and advertising research support",
         challenges="[Describe a real challenge, e.g. volume of launches or number of brand accounts.]",
         approach=["Publish and execute campaigns in Meta Business Suite.",
                   "Support advertising execution across the agency's DTC brand accounts.",
                   "[Add a specific step from your process.]"],
         tools=["Meta Business Suite"],
         deliverables=["Published campaigns", "[Add deliverables]"]),
    dict(slug="case-competitor-research", cat="research", mock="grid", tone="surface",
         title="Competitor ad research for creative direction", client=A,
         industry="Direct-to-consumer brands",
         overview="Research into competitor advertising to find creative angles and concepts the agency's brands can use as reference.",
         objective="Give the creative team a clear view of what competitors are running and which angles are worth using.",
         role="Advertising research support",
         challenges="[Describe a real challenge.]",
         approach=["Research competitor ads from [sources used].",
                   "Analyse creatives for hooks, angles, formats, and messaging.",
                   "Identify concepts that can serve as references for brands."],
         tools=["[Research tools used]"],
         deliverables=["Creative angle references", "[Research document or board format]"]),
    dict(slug="case-lead-gen", cat="leadgen", mock="form", tone="mauve",
         title="Lead generation campaigns for service businesses", client=B,
         industry="Marketing, bookkeeping, and automotive detailing",
         overview="Structuring, setting up, and managing Meta lead generation campaigns for service businesses across three industries.",
         objective="Generate enquiries for service businesses through clearly structured Meta campaigns.",
         role="Paid advertising specialist",
         challenges="[Describe a real challenge, e.g. adapting one approach to three very different industries.]",
         approach=["Structure each campaign around the business's objective.",
                   "Set up and manage campaigns in Meta.",
                   "Research competitor ads to inform creative.",
                   "Support campaign strategy and execution."],
         tools=["Meta Business Suite"],
         deliverables=["Campaign structures", "Live lead generation campaigns", "[Add deliverables]"]),
    dict(slug="case-video-ads", cat="video", mock="phone", tone="rose",
         title="Short-form video ads", client=B,
         industry="Marketing, bookkeeping, and automotive detailing",
         overview="Editing short-form video ads and producing creatives built around each business's objective.",
         objective="Give each campaign creative that fits the offer, the audience, and the format.",
         role="Creative support and video editing",
         challenges="[Describe a real challenge.]",
         approach=["Research competitor ads and creative inspiration before editing.",
                   "Edit short-form video ads.",
                   "Produce static ad creatives."],
         tools=["CapCut", "Canva"],
         deliverables=["Short-form video ads", "Static ad creatives"]),
    dict(slug="case-social", cat="social", mock="feed", tone="plum",
         title="Social media management", client=C,
         industry="[Industry]",
         overview="Managing the client's social media accounts, from planning content to producing and publishing it.",
         objective="Keep the brand's social presence consistent, organised, and on schedule.",
         role="Digital marketing and operations support",
         challenges="[Describe a real challenge.]",
         approach=["Plan content [weekly or monthly].",
                   "Produce content, including video edits.",
                   "Publish and organise content across [platforms]."],
         tools=["Social media platforms", "Canva", "CapCut"],
         deliverables=["Content plans", "Published content", "[Add deliverables]"]),
    dict(slug="case-funnels", cat="funnel", mock="browser", tone="surface",
         title="Funnels and landing pages", client=C,
         industry="[Industry]",
         overview="Building marketing funnels and landing pages that support the client's acquisition.",
         objective="Give leads a clear page to land on and a clear next step to take.",
         role="Digital marketing and operations support",
         challenges="[Describe a real challenge.]",
         approach=["Build landing pages and funnels in GoHighLevel.",
                   "Connect lead capture to the CRM.",
                   "[Describe page structure and goals.]"],
         tools=["GoHighLevel"],
         deliverables=["Landing pages", "Funnels", "[Add deliverables]"]),
    dict(slug="case-crm-automation", cat="crm", mock="flow", tone="mauve",
         title="GoHighLevel CRM and workflow automation", client=C,
         industry="[Industry]",
         overview="Setting up GoHighLevel, organising pipelines and lead journeys, and building workflow automations.",
         objective="Make sure every lead is tracked, followed up, and moved forward without manual chasing.",
         role="Digital marketing and operations support",
         challenges="[Describe a real challenge.]",
         approach=["Set up GoHighLevel for the business.",
                   "Organise CRM pipelines and lead journeys.",
                   "Build workflow automations for [follow-up, appointments, or email]."],
         tools=["GoHighLevel"],
         deliverables=["CRM pipelines", "Workflow automations", "[Add deliverables]"]),
]

MOCKS = {
    "ad": '<div class="m-ad"><i class="m-av"></i><i class="m-l s"></i><i class="m-img"></i><i class="m-l"></i><i class="m-btn"></i></div>',
    "grid": '<div class="m-grid">' + "<i></i>" * 9 + "</div>",
    "form": '<div class="m-form"><i class="m-l s"></i><i class="m-in"></i><i class="m-in"></i><i class="m-in"></i><i class="m-btn"></i></div>',
    "phone": '<div class="m-phone"><i class="m-play"></i></div>',
    "feed": '<div class="m-feed"><i></i><i></i><i></i><i></i></div>',
    "browser": '<div class="m-browser"><b><i></i><i></i><i></i></b><i class="m-hero"></i><i class="m-l"></i><i class="m-l s"></i></div>',
    "flow": '<div class="m-flow"><i class="n"></i><i class="e"></i><i class="n"></i><i class="e"></i><i class="n"></i></div>',
}

def mock(p):
    return f'<div class="mock t-{p["tone"]}" aria-hidden="true">{MOCKS[p["mock"]]}</div>'

def card(p, size):
    return f'''<a class="card {size}" href="#{p["slug"]}" data-cat="{p["cat"]}">
  {mock(p)}
  <span class="card-meta">{html.escape(CAT[p["cat"]])}<span class="sep">/</span>{html.escape(p["client"].split(",")[0])}</span>
  <h3>{html.escape(p["title"])}</h3>
</a>'''

def case(p, nxt):
    li = lambda xs: "".join(f"<li>{fmt(x)}</li>" for x in xs)
    return f'''<article class="case" id="{p["slug"]}" aria-labelledby="{p["slug"]}-t">
  <div class="wrap">
    <a class="back" href="#work">Back to selected work</a>
    <p class="case-cat">{html.escape(CAT[p["cat"]])}</p>
    <h2 id="{p["slug"]}-t" tabindex="-1">{html.escape(p["title"])}</h2>
    <p class="case-lead">{fmt(p["overview"])}</p>
    <dl class="case-facts">
      <div><dt>Client</dt><dd>{fmt(p["client"])}</dd></div>
      <div><dt>Industry</dt><dd>{fmt(p["industry"])}</dd></div>
      <div><dt>My role</dt><dd>{fmt(p["role"])}</dd></div>
      <div><dt>Tools</dt><dd>{fmt(", ".join(p["tools"]))}</dd></div>
    </dl>
    <div class="case-hero">{mock(p)}<p class="media-note">{ph("[Replace with a project screenshot or video]")}</p></div>
    <div class="case-body">
      <section><h3>Business objective</h3><p>{fmt(p["objective"])}</p></section>
      <section><h3>Challenges</h3><p>{fmt(p["challenges"])}</p></section>
      <section><h3>Approach and execution</h3><ul>{li(p["approach"])}</ul></section>
      <section><h3>Deliverables</h3><ul>{li(p["deliverables"])}</ul></section>
      <section><h3>Results</h3><p>{fmt(RESULTS)}</p></section>
    </div>
    <div class="gallery">
      <div class="shot">{ph("[Screenshot]")}</div><div class="shot">{ph("[Screenshot]")}</div><div class="shot">{ph("[Video or image]")}</div>
    </div>
    <a class="next" href="#{nxt["slug"]}"><span>Next project</span>{html.escape(nxt["title"])}</a>
  </div>
</article>'''

STAGES = [
    ("Research", "Study what is already working.",
     "Before anything is made, I look at what competitors are running: the hooks, angles, and formats that keep showing up, and the gaps nobody has used yet.",
     ["Competitor ad research", "Creative angle analysis", "Concept references"]),
    ("Creative", "Turn angles into ads.",
     "Research becomes short-form video edits and static creatives built around the campaign's objective. The same production feeds organic social content.",
     ["Short-form video editing", "Static ad creatives", "Social content"]),
    ("Launch", "Structure and publish campaigns.",
     "Campaigns are structured around one clear goal, then set up and published in Meta, with the details checked before anything goes live.",
     ["Meta Ads setup", "Campaign structure", "Lead generation campaigns"]),
    ("Capture", "Give every lead somewhere to land.",
     "Landing pages and funnels in GoHighLevel turn ad clicks into named leads inside a CRM, rather than form fills that disappear into an inbox.",
     ["Landing pages", "Funnels", "Lead capture"]),
    ("Follow-up", "Make sure no lead goes cold.",
     "Pipelines, lead journeys, and automated follow-up keep each lead moving, so the business spends its time on conversations instead of chasing.",
     ["CRM pipelines", "Workflow automation", "Email and appointment workflows"]),
]

SERVICES = [
    ("Paid advertising & lead generation", "Launch",
     "Meta campaigns structured around a clear goal, from setup through publishing and ongoing management.",
     ["Meta Ads campaign setup", "Lead generation campaigns", "Campaign structuring", "Ad publishing and management", "Competitor ad research", "Creative testing support"]),
    ("Creative research & production", "Research",
     "Ads grounded in research: what competitors run, which angles work, and creative produced to match.",
     ["Competitor ad research", "Creative concept research", "Short-form video editing", "Video ad production", "Static ad creatives", "Canva and CapCut production"]),
    ("Social media management", "Creative",
     "Consistent, organised social channels, from the content plan through production and publishing.",
     ["Content planning", "Content creation", "Video editing", "Publishing", "Content organisation"]),
    ("Funnel & CRM development", "Capture",
     "Landing pages, funnels, and GoHighLevel pipelines that turn clicks into leads you can see and manage.",
     ["GoHighLevel setup", "Landing pages", "Funnel building", "Lead capture systems", "CRM pipeline setup", "Lead journey organisation"]),
    ("Marketing automation", "Follow-up",
     "Workflows that follow up, remind, and nurture automatically, so leads move forward without manual chasing.",
     ["Workflow automation", "Automated lead follow-up", "Appointment workflows", "Email automation", "Lead nurturing", "Business process automation"]),
]

ENGAGEMENTS = [
    ("Client A", "DTC advertising agency", "Ad publisher and advertising research support",
     ["Publishing and executing ad campaigns", "Researching competitor advertisements", "Analysing creatives for usable angles and concepts", "Supporting execution for direct-to-consumer brands"],
     "Meta ad operations, creative analysis, attention to detail"),
    ("Client B", "Paid advertising & lead generation", "Paid advertising specialist and creative support",
     ["Structuring lead generation campaigns", "Setting up and managing Meta campaigns", "Editing short-form video ads", "Developing creatives around business objectives"],
     "Lead generation, campaign structure, video editing"),
    ("Client C", "Digital marketing & operations", "Digital marketing and operations support",
     ["Managing and producing social media content", "Building funnels and landing pages", "Setting up GoHighLevel and its automations", "Organising lead journeys and CRM processes"],
     "Funnels, CRM, automation, process organisation"),
]

TRAITS = [
    ("Independent", "Takes projects from planning to launch without needing step-by-step direction."),
    ("Adaptable", "Moves between ads, creative, social, and technical setup across different industries."),
    ("Execution-focused", "Turns plans into live campaigns, creatives, funnels, and workflows."),
    ("Resourceful", "Uses research and AI tools to solve problems and work more efficiently."),
    ("Detail-oriented", "Cares about clean campaign structure, tidy workflows, and accurate data."),
    ("Always learning", "Keeps picking up new tools and sharpening the craft."),
]

TOOLS = [
    ("Advertising", ["Meta Business Suite"]),
    ("CRM & automation", ["GoHighLevel"]),
    ("Social media", ["Social media platforms"]),
    ("Creative production", ["CapCut", "Canva"]),
    ("Communication & calling", ["Slack", "Kixie", "Mango"]),
    ("AI & productivity", ["ChatGPT", "Gemini", "Claude", "Microsoft Office"]),
]

PROPER = {"Meta", "Canva", "GoHighLevel", "CRM"}
def lc(x):
    return x if x.split()[0] in PROPER else x[0].lower() + x[1:]

import re
def curly(page):
    """Typographic apostrophes in visible text only; leave <style> and <script> untouched."""
    parts = re.split(r"(<style>.*?</style>|<script>.*?</script>)", page, flags=re.S)
    return "".join(p if p.startswith(("<style>", "<script>")) else re.sub(r"(?<=\w)'(?=\w)", "\u2019", p) for p in parts)

def build():
    sizes = ["w7", "w5", "w4", "w4", "w4", "w6", "w6"]
    cards = "\n".join(card(p, s) for p, s in zip(PROJECTS, sizes))
    cases = "\n".join(case(p, PROJECTS[(i + 1) % len(PROJECTS)]) for i, p in enumerate(PROJECTS))
    filters = '<button type="button" aria-pressed="true" data-filter="all">All</button>' + "".join(
        f'<button type="button" aria-pressed="false" data-filter="{k}">{html.escape(v)}</button>' for k, v in CATS)

    rail = "".join(f'<li><a href="#stage-{i+1}" data-step="{i}"><span class="rn">{i+1}</span>{s[0]}</a></li>' for i, s in enumerate(STAGES))
    stages = "".join(f'''<div class="stage" id="stage-{i+1}" data-step="{i}">
  <p class="stage-no">{i+1} <span>of 5</span></p>
  <h3><span class="stage-name">{s[0]}.</span> {s[1]}</h3>
  <p>{s[2]}</p>
  <ul class="inline">{"".join(f"<li>{x}</li>" for x in s[3])}</ul>
</div>''' for i, s in enumerate(STAGES))
    teaser = "".join(f'<li><a href="#stage-{i+1}">{s[0]}</a></li>' for i, s in enumerate(STAGES))

    services = "".join(f'''<li class="svc">
  <h3>{t}</h3>
  <div>
    <p>{d}</p>
    <p class="incl"><span>Includes</span> {", ".join(lc(x) for x in items)}.</p>
    <a class="stage-link" href="#system">Where it fits: {st}</a>
  </div>
</li>''' for t, st, d, items in SERVICES)

    eng = "".join(f'''<li class="eng">
  <p class="eng-client">{c}</p>
  <p class="eng-sector">{sec}</p>
  <h3>{role}</h3>
  <ul>{"".join(f"<li>{x}</li>" for x in tasks)}</ul>
  <p class="eng-built"><span>Strengths built</span> {built}</p>
</li>''' for c, sec, role, tasks, built in ENGAGEMENTS)

    traits = "".join(f"<li><h3>{t}</h3><p>{d}</p></li>" for t, d in TRAITS)
    tools = "".join(f'<div class="tool-group"><h3>{g}</h3><ul>{"".join(f"<li>{x}</li>" for x in items)}</ul></div>' for g, items in TOOLS)
    options = "".join(f"<option>{html.escape(t)}</option>" for t, *_ in SERVICES)

    page = open("template.html").read()
    for k, v in {
        "{{NAME}}": html.escape(NAME), "{{NAME_TXT}}": NAME, "{{CARDS}}": cards, "{{CASES}}": cases,
        "{{FILTERS}}": filters, "{{RAIL}}": rail, "{{STAGES}}": stages, "{{TEASER}}": teaser,
        "{{SERVICES}}": services, "{{ENG}}": eng, "{{TRAITS}}": traits, "{{TOOLS}}": tools,
        "{{OPTIONS}}": options,
    }.items():
        page = page.replace(k, v)
    page = curly(page)
    open("index.html", "w").write(page)
    print("built", len(page), "bytes")

if __name__ == "__main__":
    build()
