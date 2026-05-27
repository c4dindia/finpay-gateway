/**
 * Download Stitch screen HTML + screenshot for local reference.
 *
 * Usage (PowerShell):
 *   $env:STITCH_API_KEY = "your-key"
 *   node scripts/fetch-stitch-screen.mjs
 *
 * Or with Google Cloud ADC:
 *   $env:GOOGLE_CLOUD_PROJECT = "your-gcp-project"
 *   node scripts/fetch-stitch-screen.mjs
 */
import { mkdir, writeFile } from "node:fs/promises";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";
import { stitch } from "@google/stitch-sdk";

const PROJECT_ID = "292193324846419132";
const SCREEN_ID = "849bc3c3f9e048cd8fb79e3137ddd350";
const OUT_DIR = join(
    dirname(fileURLToPath(import.meta.url)),
    "..",
    "storage",
    "app",
    "stitch-reference",
    SCREEN_ID,
);

const project = stitch.project(PROJECT_ID);
const screen = await project.getScreen(SCREEN_ID);
const htmlUrl = await screen.getHtml();
const imageUrl = await screen.getImage();

await mkdir(OUT_DIR, { recursive: true });

async function download(url, filename) {
    const res = await fetch(url);
    if (!res.ok) throw new Error(`Failed ${filename}: ${res.status}`);
    const buf = Buffer.from(await res.arrayBuffer());
    await writeFile(join(OUT_DIR, filename), buf);
    console.log("Wrote", join(OUT_DIR, filename));
}

await download(htmlUrl, "screen.html");
await download(imageUrl, "screen.png");
await writeFile(
    join(OUT_DIR, "manifest.json"),
    JSON.stringify(
        {
            projectId: PROJECT_ID,
            screenId: SCREEN_ID,
            title: "Modern Fintech Dashboard Dark Mode",
            htmlUrl,
            imageUrl,
        },
        null,
        2,
    ),
);
console.log("Done:", OUT_DIR);
