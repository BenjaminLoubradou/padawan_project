<?php


namespace App\Service;


class GitHubGraphQL
{
    public function getProfileInfo($login){
        $query = <<<'GRAPHQL'
        query GetUser($user: String!) {
           user (login: $user) {
            name
            email
            avatarUrl
            bio
            repositoriesContributedTo {
              totalCount
            }
          }
        }
GRAPHQL;
        $datas = $this->graphql_query('https://api.github.com/graphql', $query, ['user' => $login], 'cdf940b8cc995cad96cb0f58cb4c4e347a46d701');
        return $datas;
    }

    public function getCommits($repository,$owner,$limit = 10) {
        $query =<<<'GRAPHQL'
           query GetCommits($owner: String!,$name: String!,$limit: Int!) {
           repository(owner: $owner, name: $name) {
            object(expression: "master") {
                ... on Commit {
                    history (first:$limit){
                        totalCount
                          nodes {
                            committedDate
                            id
                            message
                            commitUrl
                            author {
                                date
                                email
                                name
                            }
          }
        }
      }
    }
  }
}
GRAPHQL;
        $datas =  $this->graphql_query('https://api.github.com/graphql', $query, ['owner' => $owner,'name'=>$repository,'limit'=>$limit], 'cdf940b8cc995cad96cb0f58cb4c4e347a46d701');
        return $datas;
    }

    private function graphql_query(string $endpoint, string $query, array $variables = [], ?string $token = null): array
    {
        $headers = ['Content-Type: application/json', 'User-Agent: Dunglas\'s minimal GraphQL client'];
        if (null !== $token) {
            $headers[] = "Authorization: bearer $token";
        }
        if (false === $data = @file_get_contents($endpoint, false, stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => $headers,
                    'content' => json_encode(['query' => $query, 'variables' => $variables]),
                ]
            ]))) {
            $error = error_get_last();
            throw new \ErrorException($error['message'], $error['type']);
        }
        return json_decode($data, true); // d'apres l'analyse on va obtenir un tableau associatif
    }
}