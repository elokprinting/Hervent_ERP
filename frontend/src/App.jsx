function App() {
  return (
    <main className="min-h-screen bg-neutral-950 px-6 py-16 text-white sm:px-10">
      <section className="mx-auto max-w-4xl">
        <p className="text-sm font-semibold uppercase tracking-[0.24em] text-red-400">
          HERVENT ERP
        </p>
        <h1 className="mt-5 text-3xl font-semibold tracking-tight sm:text-5xl">
          Frontend siap dikembangkan
        </h1>
        <p className="mt-4 max-w-2xl text-base leading-7 text-neutral-300">
          Aplikasi React ini berjalan terpisah dari backend Laravel. Tampilan
          dan informasi dari referensi legacy akan dipertahankan saat modul
          ERP mulai diimplementasikan.
        </p>
        <div className="mt-10 grid gap-4 sm:grid-cols-2">
          <article className="rounded-xl border border-neutral-800 bg-neutral-900 p-5">
            <h2 className="font-medium">Frontend</h2>
            <p className="mt-2 text-sm text-neutral-400">
              React, Vite, dan Tailwind CSS
            </p>
          </article>
          <article className="rounded-xl border border-neutral-800 bg-neutral-900 p-5">
            <h2 className="font-medium">Backend</h2>
            <p className="mt-2 text-sm text-neutral-400">
              Laravel API dan business logic
            </p>
          </article>
        </div>
      </section>
    </main>
  )
}

export default App
