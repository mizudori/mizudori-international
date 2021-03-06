require 'tmpdir'

# Usage:
# add to ruhoh-site/plugins/publish/github.rb
# - Your GitHub remote must be setup properly but The command will try to walk you through it.
# - You must have a clean working directory to publish to GitHub pages since the hook is actually triggered by commits.
#
#   $ cd ruhoh-site
#   $ bundle exec ruhoh publish github
class Ruhoh
  class Publish::Github
    def run(opts={}, config={})
      return false unless can_switch_branch?

      _deploy_type = project_page? ? "Project" : "User/Org"
      _source_branch = source_branch
      _deploy_branch = deploy_branch
      _origin_remote = origin_remote
      Ruhoh::Friend.say {
        plain "Deploying to GitHub Pages."
        plain "(Settings based on origin remote name: #{ _origin_remote })"
        plain "      Type: #{ _deploy_type } page."
        plain "    Source: '#{ _source_branch }' branch."
        plain "  Compiled: '#{ _deploy_branch }' branch."
      }

      if deploy_branch?
        puts "Currently in deploy branch: '#{ deploy_branch }'; switching to source branch: '#{ source_branch }'..."
        `git checkout #{ source_branch }`
      end

      ruhoh = compile

      # Add to deploy_branch
      return false unless checkout_deploy_branch
      system("git", "rm", "-rf", ".")
      FileUtils.cp_r(File.join(ruhoh.config['compiled_path'], '.'), '.')
      `git add .` # system() doesn't work for some reason =/

      # Commit and push
      system("git", "commit", "-m", "#{ source_branch }: #{ last_commit_message(source_branch) }")
      system("git", "push", "origin", deploy_branch)
      system('git', 'checkout', source_branch)
    end

    def compile
      ruhoh = Ruhoh.new
      #ruhoh.setup
      ruhoh.env = 'production'
      #ruhoh.setup_paths
      ruhoh.setup_plugins

      config_overrides = set_configuration(ruhoh.config)
      ruhoh.config.merge!(config_overrides)

      ruhoh.config['compiled_path'] = File.join(Dir.tmpdir, 'compiled')
      ruhoh.compile
      ruhoh
    end

    # Set GitHub-specific configuration settings.
    def set_configuration(config)
      opts = {}
      opts['compile_as_root'] = true
      opts['base_path'] = "/"

      if project_page?
        if !config['production_url'] || config['production_url'] == "http://sample.com"
          opts['base_path'] = "/#{ repository_name }/"

          Ruhoh::Friend.say { plain "base_path set to: #{ opts['base_path'] } for GitHub project page support" }
        else
          Ruhoh::Friend.say {
            plain "base_path set to: '#{ opts['base_path'] }' because config['production_url'] = '#{ config['production_url'] }'"
          }
        end
      end

      opts
    end

    def deploy_branch
      @deploy_branch ||= project_page? ? 'gh-pages' : 'master'
    end

    def source_branch
      (deploy_branch == 'gh-pages') ? 'master' : 'gh-pages'
    end

    def deploy_branch?
      get_branch == deploy_branch
    end

    def stage_clean?
      system('git', 'diff', '--staged', '--exit-code')
    end

    def working_directory_clean?
      system('git', 'diff', '--exit-code')
    end

    def can_switch_branch?
      return true if working_directory_clean? && stage_clean?

      puts "Aborting: Deploying requires a clean working directory and staging area."
      puts "  - Commit changes to add them to the compile output."
      puts "  - `git stash` changes to omit them from compile output."
      false
    end

    def checkout_deploy_branch
      return false unless can_switch_branch?

      return true if system('git', 'checkout', deploy_branch)
      return true if system("git", "checkout", "--orphan", deploy_branch)

      puts "Aborting: Switching to #{ deploy_branch } branch failed."
      false
    end

    def get_branch
      branch = nil
      `git branch --no-color`.each_line do |line|
        if line.start_with?("*")
          branch = line
          break
        end
      end

      #omit the * and space
      branch[2, branch.length].strip
    end

    def last_commit_message(branch)
      `git show #{ branch } --summary --pretty=oneline --no-color`.lines.first
    end

    # Extract the remote URL from the origin remote signature
    # Example:
    #   origin  git@github.com:jaderade/hello-world.git (fetch)
    def origin_remote
      `git remote -v`.lines.first.split(/\s+/)[1]
    end

    # Extract the repository name from the remote Url
    # Example formats:
    #   git@github.com:jaderade/hello-world.git
    #   https://github.com/jaderade/hello-world.git
    #
    #   Should extract "hello-world" from above example.
    def repository_name
      remote = origin_remote
      remote = remote.include?(':') ?
                remote.split(':')[1] :
                remote.gsub(/^(http|https):\/\/github.com\//, '')

      # Parse username/<repo-name>.git
      remote.split('/')[1].chomp('.git')
    end

    # Does the repository name reflect a GitHub Project page?
    # Anything other than username.github.io OR username.github.com
    def project_page?
      !(repository_name =~ /[\w-]+\.github\.(?:io|com)/)
    end
  end
end