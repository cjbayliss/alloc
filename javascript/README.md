allocPSA only reads `jumbo.js`

If you make a change in `src/` you need to regenerate `jumbo.js`:

```bash
rm jumbo.js

for i in src/*; do
    cat "$i" >> jumbo.js
done
```

**FIXME:** remove jquery and jqplot