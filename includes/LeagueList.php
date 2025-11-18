<?php
/**
 * LeagueList helper
 *
 * Parses the leagues.txt file and exposes data that can be rendered in filters.
 */

class LeagueList
{
    /**
     * Load leagues grouped by region/country from a text file.
     *
     * @param string $filePath
     * @return array<int, array{region: string, leagues: array<int, array{label: string, display: string, value: string, id: ?string}>}>
     */
    public static function loadFromFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$lines) {
            return [];
        }

        $currentRegion = '';
        $groups = [];

        foreach ($lines as $line) {
            $rawLine = rtrim($line);
            if ($rawLine === '') {
                continue;
            }

            $hasIndent = strlen($rawLine) !== strlen(ltrim($rawLine));
            $descriptor = '';

            if ($hasIndent) {
                $descriptor = trim($rawLine);
            } else {
                $parts = preg_split('/\t+/', $rawLine);
                $currentRegion = trim($parts[0] ?? '');
                $descriptor = trim($parts[1] ?? '');

                // Some lines only define the region name (no descriptor)
                if ($descriptor === '' && $currentRegion !== '') {
                    if (!array_key_exists($currentRegion, $groups)) {
                        $groups[$currentRegion] = [];
                    }
                    continue;
                }
            }

            if ($descriptor === '' || $currentRegion === '') {
                continue;
            }

            $leagueId = null;
            $leagueName = $descriptor;

            if (preg_match('/#?(\\d+):\\s*(.+)/', $descriptor, $matches)) {
                $leagueId = $matches[1];
                $leagueName = $matches[2];
            }

            $displayName = trim($currentRegion . ' - ' . $leagueName);
            $value = $leagueId ?: self::slugify($displayName);

            if (!array_key_exists($currentRegion, $groups)) {
                $groups[$currentRegion] = [];
            }

            $groups[$currentRegion][] = [
                'label' => trim($leagueName),
                'display' => $displayName,
                'value' => $value,
                'id' => $leagueId,
            ];
        }

        $result = [];
        foreach ($groups as $region => $leagues) {
            $result[] = [
                'region' => $region,
                'leagues' => $leagues,
            ];
        }

        return $result;
    }

    private static function slugify(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value);
        return trim((string) $value, '-');
    }
}
